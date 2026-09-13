<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MedicaoController extends Controller
{
    public function store(Request $request)
    {
        if (strtolower(trim(explode(';', $request->header('Content-Type', ''))[0])) !== 'application/json') {
            return response()->json(['sucesso' => false, 'mensagem' => 'Use Content-Type: application/json.'], 415);
        }

        $payload = json_decode($request->getContent());
        if (json_last_error() !== JSON_ERROR_NONE || !is_object($payload)) {
            return response()->json(['sucesso' => false, 'mensagem' => 'JSON inválido: envie um objeto.'], 422);
        }

        $data = (array) $payload;
        $data['idempotency_key'] = $request->header('Idempotency-Key');
        $number = function ($attribute, $value, $fail) {
            if ((!is_int($value) && !is_float($value)) || !is_finite((float) $value)) {
                $fail('O campo ' . $attribute . ' deve ser um número JSON finito.');
            }
        };
        $validator = Validator::make($data, [
            'idempotency_key' => ['required', 'string', 'max:128', 'regex:/\A[!-~]+\z/'],
            'x' => ['required', $number],
            'y' => ['required', $number],
            'z' => ['required', $number],
            'umidade' => ['required', $number, 'numeric', 'between:0,100'],
            'temperatura' => ['required', $number],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false, 'mensagem' => 'Campos ausentes ou inválidos.',
                'erros' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $key = $data['idempotency_key'];
        try {
            if (DB::table('medicoes')->where('idempotency_key', $key)->exists()) {
                return $this->confirmar(200);
            }
            try {
                // A transação termina antes da confirmação; o índice único arbitra reenvios concorrentes.
                DB::transaction(function () use ($data) {
                    DB::table('medicoes')->insert(array_merge($data, ['recebida_em' => now()]));
                });
            } catch (QueryException $exception) {
                $state = $exception->errorInfo[0] ?? null;
                if (in_array($state, ['23000', '23505'], true)
                    && DB::table('medicoes')->where('idempotency_key', $key)->exists()) {
                    return $this->confirmar(200);
                }
                throw $exception;
            }

            return $this->confirmar(201);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['sucesso' => false, 'mensagem' => 'Falha ao salvar medição.'], 500);
        }
    }

    private function confirmar(int $status)
    {
        return response()->json(['sucesso' => true, 'mensagem' => 'Medição salva'], $status);
    }
}

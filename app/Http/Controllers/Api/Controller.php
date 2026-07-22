<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    protected function sucesso($dados = null, string $mensagem = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'sucesso' => true,
            'mensagem' => $mensagem,
            'dados' => $dados,
        ], $status);
    }

    protected function erro(string $mensagem, int $status = 400, $erros = null): JsonResponse
    {
        $payload = [
            'sucesso' => false,
            'mensagem' => $mensagem,
        ];

        if ($erros !== null) {
            $payload['erros'] = $erros;
        }

        return response()->json($payload, $status);
    }
}

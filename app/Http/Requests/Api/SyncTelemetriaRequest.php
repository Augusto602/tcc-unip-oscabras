<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SyncTelemetriaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_execucao' => 'required|integer|exists:execucoes,id_execucao',
            'timestamp' => 'nullable|date',
            'temperatura' => 'required|numeric|between:-40,80',
            'umidade' => 'required|numeric|between:0,100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'obstaculo_detectado' => 'nullable|boolean',
            'tipo_obstaculo' => 'nullable|string|max:100',
            'score_confianca' => 'nullable|numeric|between:0,1',
            'acao_executada' => 'nullable|string|max:100',
            'distancia_percorrida' => 'nullable|numeric|min:0',
            'tempo_decorrido' => 'nullable|string',
            'velocidade_media' => 'nullable|numeric|min:0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação da telemetria.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

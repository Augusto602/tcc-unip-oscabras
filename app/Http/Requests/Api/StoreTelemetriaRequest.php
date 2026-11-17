<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTelemetriaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data_hora' => 'required|date',
            'comando' => 'required|string|max:100',
            'coordenadas' => 'required|array',
            'coordenadas.x' => 'required|numeric|between:-9999.999999,9999.999999',
            'coordenadas.y' => 'required|numeric|between:-9999.999999,9999.999999',
            'coordenadas.z' => 'required|numeric',
            'coordenadas_estimadas' => 'required|boolean',
            'orientacao_graus' => 'required|numeric',
            'temperatura' => 'present|nullable|numeric|between:-40,80',
            'umidade' => 'present|nullable|numeric|between:0,100',
            'objetos_detectados' => 'present|array|max:100',
            'objetos_detectados.*' => 'required|array',
            'objetos_detectados.*.classe' => 'required|string|max:100',
            'objetos_detectados.*.confianca' => 'required|numeric|between:0,1',
            'objetos_detectados.*.caixa' => 'required|array|size:4',
            'objetos_detectados.*.caixa.*' => 'required|numeric|min:0',
            'objetos_detectados.*.coordenadas_observacao' => 'required|array',
            'objetos_detectados.*.coordenadas_observacao.x' => 'required|numeric',
            'objetos_detectados.*.coordenadas_observacao.y' => 'required|numeric',
            'objetos_detectados.*.coordenadas_observacao.z' => 'required|numeric',
            'objetos_detectados.*.data_hora_observacao' => 'required|date',
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

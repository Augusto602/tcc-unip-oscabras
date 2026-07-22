<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLogNavegacaoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_execucao' => 'required|integer|exists:execucoes,id_execucao',
            'data_hora' => 'nullable|date',
            'distancia_percorrida' => 'required|numeric|min:0',
            'tempo_decorrido' => 'required|string',
            'velocidade_media' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação do log de navegação/rota.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

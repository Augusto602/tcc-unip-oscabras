<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDadoMicroclimaticoRequest extends FormRequest
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
            'temperatura' => 'required|numeric|between:-40,80',
            'umidade' => 'required|numeric|between:0,100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação dos dados microclimáticos.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

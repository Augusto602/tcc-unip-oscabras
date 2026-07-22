<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVeiculoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_usuario' => 'nullable|integer|exists:usuarios,id_usuario',
            'nome_veiculo' => 'required|string|min:3|max:100',
            'tipo_modelo' => 'required|string|max:100',
            'fabricante' => 'required|string|max:100',
            'especificacoes_hardware' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação do veículo.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

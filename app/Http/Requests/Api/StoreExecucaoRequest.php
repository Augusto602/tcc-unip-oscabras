<?php

namespace App\Http\Requests\Api;

use App\Models\Execucao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreExecucaoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_veiculo' => 'required|integer|exists:veiculos,id_veiculo',
            'id_usuario' => 'nullable|integer|exists:usuarios,id_usuario',
            'data_execucao' => 'nullable|date',
            'local_execucao' => 'required|string|max:150',
            'status_execucao' => ['nullable', 'string', Rule::in(Execucao::STATUS)],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação da execução.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

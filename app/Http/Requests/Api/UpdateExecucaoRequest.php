<?php

namespace App\Http\Requests\Api;

use App\Models\Execucao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateExecucaoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'local_execucao' => 'sometimes|string|max:150',
            'status_execucao' => ['sometimes', 'string', Rule::in(Execucao::STATUS)],
            'data_execucao' => 'sometimes|date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na atualização da execução.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

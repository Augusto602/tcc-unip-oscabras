<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'senha' => 'required|string|min:4',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'senha.required' => 'Informe a senha.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Dados de login inválidos.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

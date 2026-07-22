<?php

namespace App\Http\Requests\Api;

use App\Models\EventoDeteccao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreEventoDeteccaoRequest extends FormRequest
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
            'tipo_evento' => ['required', 'string', Rule::in(EventoDeteccao::TIPOS)],
            'tipo_obstaculo' => 'nullable|string|max:100',
            'score_confianca' => 'nullable|numeric|between:0,1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucesso' => false,
            'mensagem' => 'Falha na validação do evento de detecção.',
            'erros' => $validator->errors(),
        ], 422));
    }
}

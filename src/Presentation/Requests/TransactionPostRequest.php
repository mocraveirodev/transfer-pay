<?php

namespace Src\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'numeric|required|gt:0',
            'payee_id' => 'required|string|uuid',
        ];
    }
}

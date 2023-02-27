<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier' => [
                'required', 'string', 'max:191',
            ],
            'reference_number' => [
                'required', 'string', 'max:191',
                'unique:income_transactions'
            ],
            'remarks' => ['required', 'string', 'max:60000'],
            'created_at' => [
                'required', 'numeric',
                'max:99999999999999999999'
            ]
        ];
    }
}

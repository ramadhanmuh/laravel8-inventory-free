<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeTransactionItemRequest extends FormRequest
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
            'item_id' => [
                'required', 'exists:items,id'
            ],
            'amount' => [
                'required', 'numeric', 'min:1',
                'max:99999999999999999999',
                function ($attribute, $value, $fail) {
                    $session = session('create-income-transaction-item');

                    if (!empty($session)) {
                        foreach ($session as $key => $data) {
                            if ($data['item_id'] == request()->item_id) {
                                if ($data['amount'] > 9999999999) {
                                    $fail('Jumlah barang telah mencapai maksimum kapasitas.');
                                }
                            }
                        }
                    }
                }
            ]
        ];
    }
}

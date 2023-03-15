<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;

class StoreExpenditureTransactionItemRequest extends FormRequest
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
                'required', 'numeric',
                function ($attribute, $value, $fail) {
                    $data = Item::getStockById($value);
                    if ($data->total < 0) {
                        $fail('Barang yang dipilih tidak valid.');
                    }
                }
            ],
            'amount' => [
                'required', 'numeric', 'min:1',
                'max:9999999999',
                function ($attribute, $value, $fail) {
                    $session = session('create-expenditure-transaction-item');

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

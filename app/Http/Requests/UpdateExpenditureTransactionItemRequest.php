<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;

class UpdateExpenditureTransactionItemRequest extends FormRequest
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
                    $session = session('edit-expenditure-transaction-item');

                    if (empty($session)) {
                        $data = Item::getStockById($value);

                        if ($data->total < 0) {
                            $fail('Barang yang dipilih tidak valid.');
                        }
                    } else {
                        $filtered = array_filter($session['deletedItems'], function ($val) use ($value) {
                            return $val['item_id'] == $value;
                        });

                        if (!count($filtered)) {
                            $data = Item::getStockById($value);
                            if ($data->total < 0) {
                                $fail('Barang yang dipilih tidak valid.');
                            }
                        }
                    }
                }
            ],
            'amount' => [
                'required', 'numeric', 'min:1',
                'max:9999999999',
                function ($attribute, $value, $fail) {
                    $session = session('edit-expenditure-transaction-item');

                    if (empty($session)) {
                        $expenditureTransactionId = $this->segment( count($this->segments()) );

                        $expenditureTransactionItem = ExpenditureTransactionItem::where(
                            'expenditure_transaction_id', '=', $expenditureTransactionId
                        )
                        ->where('item_id', '=', request()->item_id)
                        ->first();

                        if (!empty($expenditureTransactionItem)) {
                            if ($expenditureTransactionItem->amount > 9999999999) {
                                $fail('Jumlah barang telah mencapai maksimum kapasitas.');
                            }
                        }
                    } else {
                        $deletedItem = 1;

                        foreach ($session['expenditureTransactionItems'] as $key => $data) {
                            if ($data['item_id'] == request()->item_id) {
                                if ($data['amount'] > 9999999999 || (intval($data['amount']) + intval($value))  > 9999999999) {
                                    $deletedItem = 0;
                                    $fail('Jumlah barang telah mencapai maksimum kapasitas.');
                                }
                            }
                        }

                        if ($deletedItem) {
                            $filtered = array_filter($session['deletedItems'], function ($val) {
                                return $val['item_id'] == request()->item_id;
                            });

                            if (count($filtered)) {
                                foreach ($filtered as $key => $val) {
                                    if ($value > $val['amount']) {
                                        $data = Item::getStockById(request()->item_id);
    
                                        if ($data->total < 1) {
                                            $fail('Jumlah barang telah mencapai maksimum kapasitas.');
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            ]
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\IncomeTransactionItem;

class UpdateIncomeTransactionItemRequest extends FormRequest
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
                    $session = session('edit-income-transaction-item');

                    if (empty($session)) {
                        $id = $this->segment(count($this->segments()));

                        $incomeTransactionItems = IncomeTransactionItem::where(
                            'id', '=', $id
                        )
                        ->where('item_id', '=', request()->item_id)
                        ->get();

                        if (!$incomeTransactionItems->isEmpty()) {
                            if ($incomeTransactionItems->amount > 9999999999) {
                                $fail('Jumlah barang telah mencapai maksimum kapasitas.');
                            }
                        }
                    } else {
                        foreach ($session as $key => $data) {
                            if ($data->item == request()->item_id) {
                                if ($data->amount > 9999999999) {
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

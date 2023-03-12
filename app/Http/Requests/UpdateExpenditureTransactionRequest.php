<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;
use App\Models\ExpenditureTransactionItem;
use Illuminate\Validation\Rule;

class UpdateExpenditureTransactionRequest extends FormRequest
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
        $id = $this->segment(count($this->segments()));
        return [
            'picker' => [
                'required', 'string', 'max:191'
            ],
            'reference_number' => [
                'required', 'string', 'max:191',
                Rule::unique('expenditure_transactions')->ignore($id),
                function ($attribute, $value, $fail) use ($id) {
                    $session = session('edit-expenditure-transaction-item');

                    if (is_array($session['expenditureTransactionItems']) && count($session['expenditureTransactionItems']) < 1) {
                        $fail('Barang wajib dipilih.');
                        return;
                    }

                    if (!empty($session)) {
                        $itemNotFound = 0;

                        foreach ($session['expenditureTransactionItems'] as $key => $value) {
                            if (empty(Item::find($value['item_id']))) {
                                $itemNotFound = 1;
                                unset($session[$key]);
                            } else {
                                $expenditureTransactionItem = ExpenditureTransactionItem::getByExpenditureTransactionIdAndItemId(
                                    $id, $value['item_id']
                                );
    
                                if (empty($expenditureTransactionItem) && $expenditureTransactionItem->amount < $value['amount']) {
                                    $item =  Item::getStockById($value['item_id']);
    
                                    if (($item->total - $value['amount']) < 1) {
                                        $fail("Stok barang $item->description ($item->part_number) tidak cukup.");
                                    }
    
                                }
                            }

                        }

                        if ($itemNotFound) {
                            session()->put('edit-expenditure-transaction-item', $session);

                            $fail('Terdapat data barang yang tidak ada. Silahkan isi kembali formulir. Jika sudah benar, silahkan tekan tombol "Simpan" kembali.');
                        } else {

                        }
                    }
                },
            ],
            'remarks' => ['nullable', 'string', 'max:60000'],
            'created_at' => [
                'required', 'numeric',
                'max:99999999999999999999'
            ],
        ];
    }
}

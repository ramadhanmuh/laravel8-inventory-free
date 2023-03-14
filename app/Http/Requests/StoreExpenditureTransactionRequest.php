<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;

class StoreExpenditureTransactionRequest extends FormRequest
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
            'picker' => [
                'required', 'string', 'max:191',
                function ($attribute, $value, $fail) {
                    $session = session('create-expenditure-transaction-item');

                    if (empty($session)) {
                        $fail('Barang perlu dipilih terlebih dahulu.');
                        return;
                    }

                    $itemNotFound = 0;

                    foreach ($session as $key => $value) {
                        $item = Item::getStockById($value['item_id']);

                        if (empty($item) || $item->total < $value['amount']) {
                            $itemNotFound = 1;

                            unset($session[$key]);
                        }
                    }

                    if ($itemNotFound) {
                        $newSession = [];

                        foreach ($session as $key => $value) {
                            $newSession[] = $value;
                        }

                        session()->put('create-expenditure-transaction-item', $newSession);

                        $fail('Terdapat data barang yang tidak ada. Silahkan isi kembali formulir. Jika sudah benar, silahkan tekan tombol "Simpan" kembali.');
                    }
                },
            ],
            'reference_number' => [
                'required', 'string', 'max:191',
                'unique:expenditure_transactions'
            ],
            'remarks' => ['nullable', 'string', 'max:60000'],
            'created_at' => [
                'required', 'numeric',
                'max:99999999999999999999'
            ],
        ];
    }
}

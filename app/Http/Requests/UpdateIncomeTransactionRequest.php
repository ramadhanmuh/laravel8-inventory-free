<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;
use App\Models\IncomeTransactionItem;
use Illuminate\Validation\Rule;

class UpdateIncomeTransactionRequest extends FormRequest
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
            'supplier' => [
                'required', 'string', 'max:191'
            ],
            'reference_number' => [
                'required', 'string', 'max:191',
                Rule::unique('income_transactions')->ignore($id),
                function ($attribute, $value, $fail) {
                    $session = session('edit-income-transaction-item');

                    if (is_array($session['incomeTransactionItems']) && count($session['incomeTransactionItems']) < 1) {
                        $fail('Barang wajib dipilih.');
                        return;
                    }

                    if (!empty($session)) {
                        $itemNotFound = 0;

                        foreach ($session['incomeTransactionItems'] as $key => $value) {
                            if (empty(Item::find($value['item_id']))) {
                                $itemNotFound = 1;
                                unset($session[$key]);
                            }
                        }

                        if ($itemNotFound) {
                            session()->put('edit-income-transaction-item', $session);

                            $fail('Terdapat data barang yang tidak ada. Silahkan isi kembali formulir. Jika sudah benar, silahkan tekan tombol "Simpan" kembali.');
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

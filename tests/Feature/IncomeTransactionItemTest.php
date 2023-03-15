<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\IncomeTransaction;
use App\Models\IncomeTransactionItem;
use App\Models\Item;
use App\Models\UnitOfMeasurement;

class IncomeTransactionItemTest extends TestCase
{
    public function test_store_create_data()
    {
        $user = User::inRandomOrder()->first();

        $item = Item::inRandomOrder()->first();

        $url = 'income-transaction-items';

        $input = [
            'item_id' => $item->id,
            'amount' => strval(rand(1, 10000000000)),
        ];

        $response = $this->actingAs($user)
                            ->post($url, $input);

        $response->assertSessionHas(
            'status',
            'Berhasil menambahkan barang.'
        );
    }

    public function test_delete_create_data()
    {
        $user = User::inRandomOrder()->first();

        $item = Item::inRandomOrder()->first();

        $session = [
            [
                'item_id' => $item->id,
                'amount' => '1'
            ]
        ];

        $url = 'income-transaction-items';

        $url .= "/$item->id/create";

        $response = $this->actingAs($user)
                            ->withSession([
                                'create-income-transaction-item' => $session,
                            ])
                            ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus barang.'
        );
    }

    public function test_store_edit_data()
    {
        $user = User::inRandomOrder()->first();

        $item = Item::inRandomOrder()->first();

        $incomeTransactionItem = IncomeTransactionItem::inRandomOrder()->first();

        $url = "income-transaction-items/$incomeTransactionItem->income_transaction_id";

        $input = [
            'item_id' => $item->id,
            'amount' => strval(rand(1, 100)),
        ];

        $response = $this->actingAs($user)
                            ->put($url, $input);
                            
        $response->assertSessionHas(
            'status',
            'Berhasil menambahkan barang.'
        );
    }

    public function test_delete_edit_data()
    {
        $user = User::inRandomOrder()->first();

        $incomeTransactionItem = IncomeTransactionItem::inRandomOrder()
                                                        ->first();

        $item = Item::find($incomeTransactionItem->item_id);

        $unitOfMeasurement = UnitOfMeasurement::find(
            $item->unit_of_measurement_id
        );

        $session = [
            'id' => $incomeTransactionItem->income_transaction_id,
            'incomeTransactionItems' => [
                [
                    'income_transaction_id' => $incomeTransactionItem
                                                ->income_transaction_id,
                    'item_id' => $item->id,
                    'amount' => 1,
                    'item' => [
                        'part_number' => $item->part_number,
                        'description' => $item->description,
                        'unitOfMeasurement' => [
                            'short_name' => $unitOfMeasurement->short_name
                        ]
                    ]
                ]
            ]
        ];

        $url = 'income-transaction-items/';

        $url .= "$incomeTransactionItem->income_transaction_id/";

        $url .= $incomeTransactionItem->item_id;

        $response = $this->actingAs($user)
                            ->withSession([
                                'edit-income-transaction-item' => $session,
                            ])
                            ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus barang.'
        );
    }
}

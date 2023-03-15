<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenditureTransaction;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;

class ExpenditureTransactionItemTest extends TestCase
{
    public function test_store_create_data()
    {
        $user = User::inRandomOrder()->first();

        $item = Item::inRandomOrder()->first();

        $input['amount'] = rand(1, 50);

        $stock = Item::getStockById($item->id);

        while ($stock->total < $input['amount']) {
            $item = Item::inRandomOrder()->first();
            $stock = Item::getStockById($item->id);
        }

        $input['item_id'] = $item->id;

        $url = 'expenditure-transaction-items';

        $response = $this->actingAs($user)
                            ->post($url, $input);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
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

        $url = 'expenditure-transaction-items';

        $url .= "/$item->id/create";

        $response = $this->actingAs($user)
                            ->withSession([
                                'create-expenditure-transaction-item' => $session,
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

        $expenditureTransactionItem = ExpenditureTransactionItem::inRandomOrder()
                                                                ->first();

        $url = "expenditure-transaction-items/$expenditureTransactionItem->expenditure_transaction_id";

        $input = [
            'item_id' => $item->id,
            'amount' => strval(rand(1, 100)),
        ];

        $stock = Item::getStockById($item->id);

        while ($stock->total < $input['amount']) {
            $item = Item::inRandomOrder()->first();
            $stock = Item::getStockById($item->id);
        }

        $input['item_id'] = $item->id;

        $response = $this->actingAs($user)
                            ->put($url, $input);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas(
            'status',
            'Berhasil menambahkan barang.'
        );
    }

    public function test_delete_edit_data()
    {
        $user = User::inRandomOrder()->first();

        $expenditureTransactionItem = ExpenditureTransactionItem::inRandomOrder()
                                                        ->first();

        $item = Item::find($expenditureTransactionItem->item_id);

        $unitOfMeasurement = UnitOfMeasurement::find(
            $item->unit_of_measurement_id
        );

        $session = [
            'id' => $expenditureTransactionItem->expenditure_transaction_id,
            'expenditureTransactionItems' => [
                [
                    'expenditure_transaction_id' => $expenditureTransactionItem
                                                ->expenditure_transaction_id,
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

        $url = 'expenditure-transaction-items/';

        $url .= "$expenditureTransactionItem->expenditure_transaction_id/";

        $url .= $expenditureTransactionItem->item_id;

        $response = $this->actingAs($user)
                            ->withSession([
                                'edit-expenditure-transaction-item' => $session,
                            ])
                            ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus barang.'
        );
    }
}
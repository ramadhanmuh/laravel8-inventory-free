<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenditureTransaction;
use App\Models\Item;
use Illuminate\Support\Str;

class ExpenditureTransactionTest extends TestCase
{
    public function test_index()
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)
                        ->get('expenditure-transactions');

        $response->assertStatus(200);
    }

    public function test_index_with_order()
    {
        $user = User::inRandomOrder()->first();

        $url = 'expenditure-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_and_keyword()
    {
        $user = User::inRandomOrder()->first();

        $url = 'expenditure-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $url .= '&keyword=aut';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_keyword_and_date()
    {
        $user = User::inRandomOrder()->first();

        $url = 'expenditure-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $url .= '&keyword=aut';

        $url .= '&start_date=939489834&end_date=9324923894';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::inRandomOrder()->first();

        $url = 'expenditure-transactions/create';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_data()
    {
        $user = User::inRandomOrder()->first();

        $url = 'expenditure-transactions';

        $input = [
            'created_at' => strval(rand(100000, 1000000)),
            'reference_number' => strval(rand(10000, 1000000)),
            'picker' => Str::random(10),
            'remarks' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, minus.'
        ];

        $session = [];

        $stock = [];

        for ($i=0; $i < 5; $i++) {
            $item = Item::inRandomOrder()->first();

            $itemStock = Item::getStockById($item->id);

            while ($itemStock->total < 50) {
                $item = Item::inRandomOrder()->first();
                $itemStock = Item::getStockById($item->id);
            }

            if (!empty($session)) {
                foreach ($session as $key => $value) {
                    $sameItem = $item->id == $value['item_id'];

                    while ($sameItem || $itemStock->total < 50) {
                        $item = Item::inRandomOrder()->first();
                        $itemStock = Item::getStockById($item->id);
                    }
                }
            }

            array_push($session, [
                'item_id' => $item->id,
                'amount' => rand(1, 50)
            ]);

            array_push($stock, $itemStock->total);
        }

        $response = $this->actingAs($user)
                            ->withSession([
                                'create-expenditure-transaction-item' => $session,
                            ])
                            ->post($url, $input);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect('expenditure-transactions')
                    ->assertSessionHas(
                        'status',
                        'Berhasil menambah transaksi (keluar).'
                    );
    }

    public function test_update_page()
    {
        $user = User::inRandomOrder()->first();

        $data = ExpenditureTransaction::inRandomOrder()->first();

        $url = "expenditure-transactions/$data->id/edit";

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_update_data()
    {
        $user = User::inRandomOrder()->first();

        $data = ExpenditureTransaction::inRandomOrder()->first();

        $input = [
            'created_at' => strval(rand(100000, 1000000)),
            'reference_number' => strval(rand(10000, 1000000)),
            'picker' => Str::random(10),
            'remarks' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, minus.'
        ];

        $session = [
            'id' => $data->id,
            'expenditureTransactionItems' => []
        ];

        for ($i=0; $i < 5; $i++) {
            $expenditureTransactionItem = [
                'amount' => rand(1, 50)
            ];

            $item = Item::inRandomOrder()->first();

            $itemStock = Item::getStockById($item->id);

            while ($itemStock->total < $expenditureTransactionItem['amount']) {
                $item = Item::inRandomOrder()->first();
                $itemStock = Item::getStockById($item->id);
            }

            if (!empty($session['expenditureTransactionItems'])) {
                foreach ($session['expenditureTransactionItems'] as $key => $value) {
                    while ($value['item_id'] == $item->id || $itemStock->total < $expenditureTransactionItem['amount']) {
                        $item = Item::inRandomOrder()->first();
                        $itemStock = Item::getStockById($item->id);
                        $expenditureTransactionItem['amount'] = 1;
                    }
                }
            }
            
            $expenditureTransactionItem['item_id'] = $item->id;

            array_push($session['expenditureTransactionItems'], $expenditureTransactionItem);
        }

        $url = "expenditure-transactions/$data->id";

        $response = $this->actingAs($user)
                            ->withSession([
                                'edit-expenditure-transaction-item' => $session
                            ])
                            ->put($url, $input);

        $response->assertRedirect('expenditure-transactions')
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah transaksi (keluar).'
                    );
    }

    public function test_delete_data()
    {
        $user = User::inRandomOrder()->first();

        $data = ExpenditureTransaction::inRandomOrder()->first();

        $url = "expenditure-transactions/$data->id";

        $response = $this->actingAs($user)
                        ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus transaksi (keluar).'
        );
    }
}

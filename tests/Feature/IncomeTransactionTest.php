<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\IncomeTransaction;
use App\Models\Item;
use Illuminate\Support\Str;

class IncomeTransactionTest extends TestCase
{
    public function test_index()
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)
                        ->get('income-transactions');

        $response->assertStatus(200);
    }

    public function test_index_with_order()
    {
        $user = User::inRandomOrder()->first();

        $url = 'income-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_and_keyword()
    {
        $user = User::inRandomOrder()->first();

        $url = 'income-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $url .= '&keyword=aut';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_keyword_and_date()
    {
        $user = User::inRandomOrder()->first();

        $url = 'income-transactions?';

        $url .= 'order_by=created_at&order_direction=desc';

        $url .= '&keyword=aut';

        $url .= '&start_date=939489834&end_date=9324923894';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'income-transactions/create';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_data()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'income-transactions';

        $input = [
            'created_at' => strval(rand(100000, 1000000)),
            'reference_number' => strval(rand(10000, 1000000)),
            'supplier' => Str::random(10),
            'remarks' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, minus.'
        ];

        $session = [];

        for ($i=0; $i < 5; $i++) {
            $item = Item::inRandomOrder()->first();
            
            while (!empty(Item::find($item->id))) {
                $item = Item::inRandomOrder()->first();
            }

            array_push($session, [
                'item_id' => $item->id,
                'amount' => rand(1, 50)
            ]);
        }

        $response = $this->actingAs($user)
                            ->withSession([
                                'create-income-transaction-item' => $session
                            ])
                            ->post($url, $input);

        $response->assertRedirect('income-transactions')
                    ->assertSessionHas(
                        'status',
                        'Berhasil menambah transaksi (masuk).'
                    );
    }

    public function test_update_page()
    {
        $user = User::where('role', 'Admin')->first();

        $data = IncomeTransaction::inRandomOrder()->first();

        $url = "income-transactions/$data->id/edit";

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_update_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = IncomeTransaction::inRandomOrder()->first();

        $input = [
            'created_at' => strval(rand(100000, 1000000)),
            'reference_number' => strval(rand(10000, 1000000)),
            'supplier' => Str::random(10),
            'remarks' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, minus.'
        ];

        $session = [];

        for ($i=0; $i < 5; $i++) {
            $item = Item::inRandomOrder()->first();
            
            while (!empty(Item::find($item->id))) {
                $item = Item::inRandomOrder()->first();
            }

            array_push($session, [
                'item_id' => $item->id,
                'amount' => rand(1, 50)
            ]);
        }

        $url = "income-transactions/$data->id";

        $response = $this->actingAs($user)
                            ->withSession([
                                'edit-income-transaction-item' => $session
                            ])
                            ->put($url, $input);

        $response->assertRedirect('income-transactions')
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah transaksi (masuk).'
                    );
    }

    public function test_delete_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = IncomeTransaction::inRandomOrder()->first();

        $url = "income-transactions/$data->id";

        $response = $this->actingAs($user)
                        ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus transaksi (masuk).'
        );
    }
}

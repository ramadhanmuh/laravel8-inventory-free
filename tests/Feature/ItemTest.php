<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;

class ItemTest extends TestCase
{
    public function test_index()
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)
                        ->get('items');

        $response->assertStatus(200);
    }

    public function test_index_with_order()
    {
        $user = User::inRandomOrder()->first();

        $url = 'items?';

        $url .= 'order_by=part_number&order_direction=desc';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_and_keyword()
    {
        $user = User::inRandomOrder()->first();

        $url = 'items?';

        $url .= 'order_by=part_number&order_direction=desc';

        $url .= '&keyword=aut';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'items/create';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_data()
    {
        $user = User::where('role', 'Admin')->first();

        $category = Category::inRandomOrder()->first();

        $brand = Brand::inRandomOrder()->first();

        $unitOfMeasurement = UnitOfMeasurement::inRandomOrder()->first();

        $url = 'items';

        $input = [
            'part_number' => strval(rand(100000, 999999)),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_of_measurement_id' => $unitOfMeasurement->id,
            'description' => 'Lorem ipsum',
            'price' => '1000'
        ];

        $partNumberData = Item::where('part_number', $input['part_number'])
                                ->first();

        while (!empty($partNumberData)) {
            $input['part_number'] = strval(rand(100000, 999999));
            $partNumberData = Item::where('part_number', $input['part_number'])
                                    ->first();
        }

        $response = $this->actingAs($user)
                        ->post($url, $input);

        $response->assertRedirect('items')
                    ->assertSessionHas(
                        'status',
                        'Berhasil menambah barang.'
                    );
    }

    public function test_update_page()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Item::inRandomOrder()->first();

        $url = "items/$data->id/edit";

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_update_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Item::inRandomOrder()->first();

        $category = Category::inRandomOrder()->first();

        $brand = Brand::inRandomOrder()->first();

        $unitOfMeasurement = UnitOfMeasurement::inRandomOrder()->first();

        $input = [
            'part_number' => strval(rand(1000, 10000)),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_of_measurement_id' => $unitOfMeasurement->id,
            'description' => 'Lorem ipsum',
            'price' => '1000'
        ];

        $url = "items/$data->id";

        $response = $this->actingAs($user)
                        ->put($url, $input);

        $response->assertRedirect('items')
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah barang.'
                    );
    }

    public function test_delete_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Item::inRandomOrder()->first();

        $url = "items/$data->id";

        $response = $this->actingAs($user)
                        ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus barang.'
        );
    }
}

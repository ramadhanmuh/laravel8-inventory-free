<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;

class UnitOfMeasurementTest extends TestCase
{
    public function test_index()
    {
        $user = User::where('role', 'Admin')->first();

        $response = $this->actingAs($user)
                        ->get('unit-of-measurements');

        $response->assertStatus(200);
    }

    public function test_index_with_order()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'unit-of-measurements?';

        $url .= 'order_by=short_name&order_direction=desc';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_index_with_order_and_keyword()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'unit-of-measurements?';

        $url .= 'order_by=short_name&order_direction=desc';

        $url .= '&keyword=aut';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'unit-of-measurements/create';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_data()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'unit-of-measurements';

        $input = [
            'id' => '053e5092-3453-3af3-b154-0880e7e3377b',
            'short_name' => 'tst',
            'full_name' => 'test'
        ];

        $response = $this->actingAs($user)
                        ->post($url, $input);

        $response->assertRedirect('unit-of-measurements')
                    ->assertSessionHas(
                        'status',
                        'Berhasil menambah satuan barang.'
                    );
    }

    public function test_update_page()
    {
        $user = User::where('role', 'Admin')->first();

        $data = UnitOfMeasurement::inRandomOrder()->first();

        $url = "unit-of-measurements/$data->id/edit";

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_update_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = UnitOfMeasurement::inRandomOrder()->first();

        $url = "unit-of-measurements/$data->id";

        $input = [
            'short_name' => 'tst',
            'full_name' => 'test'
        ];

        $response = $this->actingAs($user)
                        ->put($url, $input);

        $response->assertRedirect('unit-of-measurements')
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah satuan barang.'
                    );
    }

    public function test_delete_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = UnitOfMeasurement::inRandomOrder()->first();

        $url = "unit-of-measurements/$data->id";

        $response = $this->actingAs($user)
                        ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus satuan barang.'
        );
    }
}

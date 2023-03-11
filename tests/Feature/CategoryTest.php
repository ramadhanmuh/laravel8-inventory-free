<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryTest extends TestCase
{
    public function test_index()
    {
        $user = User::where('role', 'Admin')->first();

        $response = $this->actingAs($user)
                        ->get('categories');

        $response->assertStatus(200);
    }

    public function test_index_with_order()
    {
        $user = User::where('role', 'Admin')->first();

        $response = $this->actingAs($user)
                        ->get('categories?order_by=name&order_direction=desc');

        $response->assertStatus(200);
    }

    public function test_index_with_order_and_keyword()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'categories?order_by=name&order_direction=desc';

        $url .= '&keyword=aut';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'categories/create';

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_create_data()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'categories';

        $input = [
            'id' => '053e5092-3453-3af3-b154-0880e7e3377b',
            'name' => 'test'
        ];

        $response = $this->actingAs($user)
                        ->post($url, $input);

        $response->assertRedirect('categories')
                    ->assertSessionHas(
                        'status',
                        'Berhasil menambah kategori.'
                    );
    }

    public function test_update_page()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Category::inRandomOrder()->first();

        $url = "categories/$data->id/edit";

        $response = $this->actingAs($user)
                        ->get($url);

        $response->assertStatus(200);
    }

    public function test_update_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Category::inRandomOrder()->first();

        $url = "categories/$data->id";

        $input = [
            'name' => 'test'
        ];

        $response = $this->actingAs($user)
                        ->put($url, $input);

        $response->assertRedirect('categories')
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah kategori.'
                    );
    }

    public function test_delete_data()
    {
        $user = User::where('role', 'Admin')->first();

        $data = Category::inRandomOrder()->first();

        $url = "categories/$data->id";

        $response = $this->actingAs($user)
                        ->delete($url);

        $response->assertSessionHas(
            'status', 'Berhasil menghapus kategori.'
        );
    }
}

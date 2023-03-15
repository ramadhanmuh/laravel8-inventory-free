<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    public function test_index_without_login()
    {
        $response = $this->get('users');

        $response->assertStatus(302);

        $response->assertRedirectContains('/');
    }

    public function test_index_return_data_without_filter()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users');

        $response->assertStatus(200);
    }

    public function test_index_return_data_with_spesified_page()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users?page=2');

        $response->assertStatus(200);
    }

    public function test_index_return_data_with_role_filter()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users?role=Admin');

        $response->assertStatus(200);
    }

    public function test_index_return_data_with_role_filter_and_column_sort()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users?role=Admin&order_by=name');

        $response->assertStatus(200);
    }

    public function test_index_return_data_with_role_filter_and_column_sort_and_keyword()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users?role=Admin&order_by=name&keyword=abbigail');

        $response->assertStatus(200);
    }

    public function test_index_return_data_with_role_filter_and_column_sort_and_keyword_and_spesified_page()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users?role=Admin&order_by=name&keyword=abbigail&page=2');

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users/create');

        $response->assertStatus(200);
    }

    public function test_create_page_without_login()
    {
        $response = $this->get('users/create');

        $response->assertStatus(302);

        $response->assertRedirect('/');
    }

    public function test_store_validation_error()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->post('users', [
                                'name' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusantium consequatur, earum ipsa eaque laboriosam doloremque dolores eveniet sint tempora aspernatur maxime ex suscipit a ullam ipsam neque magni nihil doloribus.',
                                'username' => 'mk^5&&&',
                                'email' => 'kmaskmakm',
                                'password' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto est quia reprehenderit nemo repudiandae hic iste odit, sint doloribus asperiores eos ipsam, tempora sunt quo cumque quae dignissimos. Dolores, quidem!',
                                'role' => 'lorem'
                            ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name', 'username', 'email', 'password', 'email', 'role'
        ]);
    }

    public function test_store_success()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $roles = ['Admin', 'Operator'];

        $input = [
            'name' => Str::random(20),
            'username' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Str::random(10),
            'role' => Arr::random($roles)
        ];

        $usernameData = User::where('username', $input['username'])
                            ->first();

        $emailData = User::where('email', $input['email'])
                            ->first();

        while (!empty($usernameData)) {
            $input['username'] = Str::random(10);
        }

        while (!empty($emailData)) {
            $input['email'] = Str::random(10) . '@gmail.com';
        }

        $response = $this->actingAs($user)
                            ->post('users', $input);

        $response->assertStatus(302);

        $response->assertRedirect('users');
    }

    public function test_edit_page()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $data = User::where('id', '!=', $user->id)->first();

        $url = "users/$data->id/edit";

        $response = $this->actingAs($user)
                            ->get('users/30/edit');

        $response->assertStatus(200);

        $response->assertSee('Ubah Pengguna');
    }

    public function test_update_validation_error()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->put('users/30', [
                                'name' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusantium consequatur, earum ipsa eaque laboriosam doloremque dolores eveniet sint tempora aspernatur maxime ex suscipit a ullam ipsam neque magni nihil doloribus.',
                                'username' => 'mk^5&&&',
                                'email' => 'kmaskmakm',
                                'password' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto est quia reprehenderit nemo repudiandae hic iste odit, sint doloribus asperiores eos ipsam, tempora sunt quo cumque quae dignissimos. Dolores, quidem!',
                                'role' => 'lorem'
                            ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name', 'username', 'email', 'password', 'email', 'role'
        ]);
    }

    public function test_update_success()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $data = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->first();

        $roles = ['Admin', 'Operator'];

        $input = [
            'name' => Str::random(20),
            'username' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Str::random(10),
            'role' => Arr::random($roles)
        ];

        $usernameData = User::where('username', $input['username'])
                            ->where('id', '!=', $data->id)
                            ->first();

        $emailData = User::where('email', $input['email'])
                            ->where('id', '!=', $data->id)
                            ->first();

        while (!empty($usernameData)) {
            $input['username'] = Str::random(10);
        }

        while (!empty($emailData)) {
            $input['email'] = Str::random(10) . '@gmail.com';
        }

        $url = "users/$data->id";

        $response = $this->actingAs($user)
                            ->put($url, $input);

        $response->assertStatus(302);

        $response->assertRedirect('users');
    }

    public function test_detail()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users/12');

        $response->assertStatus(200);

        $response->assertSee('Detail Pengguna');
    }

    public function test_delete()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $data = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->first();

        $url = "users/$data->id";

        $response = $this->actingAs($user)
                            ->delete($url);

        $response->assertStatus(302);

        $response->assertSessionHas('status', 'Berhasil menghapus pengguna.');
    }
}

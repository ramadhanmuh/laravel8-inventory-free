<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileTest extends TestCase
{
    public function test_page()
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)->get('profile');

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $user = User::where('username', 'admin')->first();

        $input = [
            'name' => 'John Doe',
            'username' => 'admin',
            'email' => 'mrama08282@gmail.com',
            'password' => 'admin'
        ];

        $response = $this->actingAs($user)
                        ->put('profile', $input);

        $response->assertRedirect('profile')
                    ->assertSessionHas('status', '1');
    }
}

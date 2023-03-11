<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ChangePasswordTest extends TestCase
{
    public function test_page()
    {
        $user = User::where('username', 'admin')->first();

        $response = $this->actingAs($user)
                            ->get('change-password');

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $user = User::where('username', 'admin')->first();

        $input = [
            'old_password' => 'admin',
            'newpassword' => 'admin',
            'newpassword_confirmation' => 'admin'
        ];

        $response = $this->actingAs($user)
                            ->put('change-password', $input);

        $response->assertRedirect('change-password')
                ->assertSessionHas('status', '1');
    }
}

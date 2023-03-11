<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    public function test_with_login()
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)->post('logout');

        $response->assertRedirect('/');
    }

    public function test_without_login()
    {
        $response = $this->post('logout');

        $response->assertRedirect('/');
    }
}

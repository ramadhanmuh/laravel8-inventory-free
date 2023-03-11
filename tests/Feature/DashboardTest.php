<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    public function test_page()
    {
        $user = User::inRandomOrder()->first();
        
        $response = $this->actingAs($user)
                        ->get('dashboard');

        $response->assertStatus(200);
    }

    public function test_without_login()
    {        
        $response = $this->get('dashboard');

        $response->assertRedirect('/');
    }
}

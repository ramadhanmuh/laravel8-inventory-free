<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_index_return_users_without_filter_requires_authentication_admin()
    {
        $user = User::where('role', '=', 'Admin')->first();

        $response = $this->actingAs($user)
                            ->get('users');

        $response->assertStatus(200);
        
    }
}

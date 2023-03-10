<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_page()
    {
        $response = $this->get('');

        $response->assertStatus(200);
    }

    public function test_login_success()
    {
        $response = $this->post('', [
            'username' => 'admin',
            'password' => 'admin'
        ]);


        $response->assertStatus(302);

        $response->assertRedirect('dashboard');
    }

    public function test_login_validation_failure()
    {
        $response = $this->post('', [
            'username' => '',
            'password' => ''
        ]);


        $response->assertStatus(302);

        $response->assertRedirectContains('/');

        $response->assertSessionHasErrors([
            'username', 'password'
        ]);
    }

    public function test_login_user_not_registered()
    {
        $response = $this->post('', [
            'username' => 'asdasd',
            'password' => 'asdasd'
        ]);


        $response->assertStatus(302);

        $response->assertRedirectContains('/');

        $response->assertSessionHasErrors([
            'username'
        ]);
    }
}

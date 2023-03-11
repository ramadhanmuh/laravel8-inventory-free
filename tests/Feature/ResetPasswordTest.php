<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordTest extends TestCase
{
    public function test_page()
    {
        $input = [
            'email' => 'mrama08282@gmail.com',
            'token' => Str::random(50)
        ];

        DB::table('password_resets')->insert($input);

        $response = $this->get('reset-password/' . $input['token']);

        $response->assertStatus(200);
    }

    public function test_submit_form()
    {
        $input = [
            'email' => 'mrama08282@gmail.com',
            'token' => Str::random(50)
        ];

        DB::table('password_resets')->insert($input);

        $response = $this->post('reset-password', [
            'token' => $input['token'],
            'email' => $input['email'],
            'password' => 'admin',
            'password_confirmation' => 'admin'
        ]);

        $response->assertRedirect('/');
    }
}

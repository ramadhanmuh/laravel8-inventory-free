<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_page()
    {
        $response = $this->get('forgot-password');

        $response->assertStatus(200)
                    ->assertSee('Lupa Kata Sandi');
    }

    public function test_send()
    {
        $input = [
            'email' => 'mrama08282@gmail.com'
        ];

        $response = $this->post('forgot-password', $input);

        $response->assertSessionHas(
            'status',
            'Berhasil mengirim email. Silahkan buka email untuk menuju halaman ubah kata sandi.'
        );
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Application;

class ApplicationTest extends TestCase
{
    public function test_edit_page()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'application';

        $response = $this->actingAs($user)
                            ->get($url);

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $user = User::where('role', 'Admin')->first();

        $url = 'application';

        $input = [
            'name' => 'Aplikasi Inventaris',
            'copyright' => 'Inventaris 2023'
        ];

        $response = $this->actingAs($user)
                            ->put($url, $input);

        $response->assertRedirect($url)
                    ->assertSessionHas(
                        'status',
                        'Berhasil mengubah pengaturan aplikasi.'
                    );
    }
}
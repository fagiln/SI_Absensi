<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_membuka_halaman_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('name="username"',false);
        $response->assertSee('name="password"',false);
        $response->assertSee('type="submit"',false);
    }

    public function test_login_admin_dengan_usernamepassword_benar(): void
    {

        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_login_admin_dengan_usernamepassword_salah(): void
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => 'abcdefg',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_admin_dengan_usernamepassword_kosong(): void
    {
        $response = $this->post('/',[
            'username' => '',
            'password' => 'abcdefg',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_harus_login_untuk_akses_menu()
    {
        $response = $this->get('/admin/karyawan');
        $response->assertRedirect('/');
    }
}

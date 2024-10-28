<?php

namespace Tests\Feature\Auth;
use App\Models\User;
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

        $admin=User::where('nik','0000001')->firstOrFail();
        $response = $this->post('/',[
            'username' => $admin->username,
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_user_dengan_usernamepassword_benar()
    {

        $user=User::where('nik','081251')->firstOrFail();
        $response = $this->post('/',[
            'username' => $user->username,
            'password' => 'user123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_dengan_usernamepassword_salah(): void
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => 'abcdefg',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_dengan_usernamepassword_kosong(): void
    {
        $response = $this->post('/',[
            'username' => '',
            'password' => 'abcdefg',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_tidak_bisa_akses_menu_tanpa_login()
    {
        $response = $this->get('/admin/karyawan');
        $response->assertRedirect('/');
    }
}

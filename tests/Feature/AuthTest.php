<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user for testing
        Admin::create([
            'username' => 'admin',
            'password' => 'password123'
        ]);
    }

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }

    public function test_logged_in_admin_is_redirected_from_login_page()
    {
        $this->withSession(['admin' => 'admin']);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_login_with_correct_credentials()
    {
        $response = $this->post(route('login.post'), [
            'username' => 'admin',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertNotNull(session('admin'));
    }

    public function test_admin_cannot_login_with_wrong_username()
    {
        $response = $this->post(route('login.post'), [
            'username' => 'wronguser',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_admin_cannot_login_with_wrong_password()
    {
        $response = $this->post(route('login.post'), [
            'username' => 'admin',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_logout()
    {
        $this->withSession(['admin' => 'admin']);

        $response = $this->get(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertNull(session('admin'));
    }

    public function test_login_requires_username()
    {
        $response = $this->post(route('login.post'), [
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_login_requires_password()
    {
        $response = $this->post(route('login.post'), [
            'username' => 'admin'
        ]);

        $response->assertSessionHasErrors('password');
    }
}

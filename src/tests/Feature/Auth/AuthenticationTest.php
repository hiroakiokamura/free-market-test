<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_validation_message_shown_when_empty()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_email_validation_message_shown_when_invalid_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを正しい形式で入力してください'
        ]);
    }

    public function test_password_validation_message_shown_when_empty()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_password_validation_message_shown_when_incorrect()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors([
            'email' => 'パスワードが一致しません'
        ]);
    }

    public function test_successful_login_redirects_to_home()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_logout_successful()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
        $response->assertSessionHas('status', 'ログアウトしました。');
    }
} 
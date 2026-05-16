<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    public function test_root_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_dashboard_requires_auth(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $this->actingAsAdmin();
        $this->get('/dashboard')->assertOk()->assertSee('Dashboard');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->makeUser('Admin');

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser('Admin');

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $this->actingAsAdmin();
        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();
    }
}
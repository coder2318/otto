<?php

namespace Tests\Feature\Services;

use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function service(): AuthService
    {
        return $this->app->make(AuthService::class);
    }

    /** @test */
    public function create_user()
    {
        $user = $this->service()->create([
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertTrue(Hash::check('password', $user->password));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function reset_user_password()
    {
        $user = $this->createUser();

        $this->service()->reset($user, [
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    /** @test */
    public function update_user_password()
    {
        $user = $this->createUser();

        Auth::login($user);

        $this->service()->update($user, [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $this->assertTrue(Hash::check('new_password', $user->password));
    }
}

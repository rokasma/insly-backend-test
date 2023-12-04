<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginIsSuccessful(): void
    {
        UserFactory::new()->create([
            'email' => 'car@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $this->postJson(route('auth.login'), [
            'email' => 'car@gmail.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertSee('token');

        $this->assertDatabaseHas('users', [
            'email' => 'car@gmail.com'
        ]);
    }

    public function testLogoutIsSuccessful(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertStatus(204);

        $this->assertNull($user->tokens()->find($token));
    }
}

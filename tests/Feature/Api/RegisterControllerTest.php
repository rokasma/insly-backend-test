<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public static function loginValidations(): array
    {
        return [
            [
                [
                    'first_name' => null,
                    'last_name' => null,
                    'email' => null,
                    'password' => null,
                    'address' => null,
                ],
                [
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                ],
            ],
            [
                [
                    'first_name' => 123,
                    'last_name' => 312,
                    'email' => 'not email',
                    'password' => 'short',
                    'address' => true,
                ],
                [
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                ]
            ]
        ];
    }

    /** @dataProvider loginValidations */
    public function testRegistrationValidations(array $requestBody, array $errors)
    {
        $this
            ->postJson(route('auth.register'), $requestBody)
            ->assertUnprocessable()
            ->assertJsonValidationErrors($errors);
    }

    public function testRegistrationIsSuccessful(): void
    {
        $this->postJson(route('auth.register'), [
            'first_name' => 'rokas',
            'last_name' => 'martus',
            'email' => 'rokas@gmail.com',
            'password' => 'password123',
            'address' => null,
        ])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('users', [
            'first_name' => 'rokas',
            'last_name' => 'martus',
            'email' => 'rokas@gmail.com',
        ]);

        $this->assertDatabaseEmpty('user_details');
    }

    public function testRegistrationWithAddressIsSuccessful(): void
    {
        $this->postJson(route('auth.register'), [
            'first_name' => 'rokas',
            'last_name' => 'martus',
            'email' => 'rokas@gmail.com',
            'password' => 'password123',
            'address' => 'Kauno g. 123, Kaunas',
        ])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('users', [
            'first_name' => 'rokas',
            'last_name' => 'martus',
            'email' => 'rokas@gmail.com',
        ]);

        $this->assertDatabaseHas('user_details', [
            'address' => 'Kauno g. 123, Kaunas',
        ]);
    }
}

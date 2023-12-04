<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\UserDetail;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateUserWillBeSuccessful(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create(['first_name' => 'rokas']);

        $user->createToken('test-token')->plainTextToken;

        $this->assertDatabaseHas('users', [
            'first_name' => 'rokas'
        ]);

        $this->actingAs($user)->postJson('/api/users/update/' . $user->id, [
            'first_name' => 'tomas',
            'last_name' => 'smith',
            'email' => 'tomas@gmail.com',
        ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'first_name' => 'tomas',
            'last_name' => 'smith',
            'email' => 'tomas@gmail.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'first_name' => 'rokas',
        ]);
    }

    public static function updateValidations(): array
    {
        return [
            [
                [
                    'first_name' => null,
                    'last_name' => null,
                    'email' => null,
                ],
                [
                    'first_name',
                    'last_name',
                    'email',
                ],
            ],
            [
                [
                    'first_name' => 123,
                    'last_name' => false,
                    'email' => 18723,
                ],
                [
                    'first_name',
                    'last_name',
                    'email',
                ],
            ]
        ];
    }

    /** @dataProvider updateValidations */
    public function testUpdateUserValidations(array $requestBody, array $errors): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create(['first_name' => 'Alex']);
        $user->createToken('test-token')->plainTextToken;

        $this
            ->actingAs($user)
            ->postJson('/api/users/update/' . $user->id, $requestBody)
            ->assertUnprocessable()
            ->assertJsonValidationErrors($errors);
    }

    public function testUserListWillBeSuccessful(): void
    {
        $this->freezeTime();

        /** @var User $user1 */
        $user1 = UserFactory::new()->create(['email' => 'rokas@gmail.com']);
        /** @var User $user2 */
        $user2 = UserFactory::new()->create(['email' => 'tomas@gmail.com']);
        /** @var User $user3 */
        $user3 = UserFactory::new()->create(['email' => 'jonas@gmail.com']);

        /** @var UserDetail $userDetail */
        $userDetail = UserDetail::factory()->withUser($user3)->create();

        $user1->createToken('test-token')->plainTextToken;

        $this
            ->actingAs($user1)
            ->getJson(route('user.list'))
            ->assertOk()
            ->assertExactJson([
                [
                    "id" => $user1->id,
                    "firstName" => $user1->first_name,
                    "lastName" => $user1->last_name,
                    "email" => 'rokas@gmail.com',
                    "address" => null,
                    "created_at" => $user1->created_at,
                    "updated_at" => $user1->updated_at,
                ],
                [
                    "id" => $user2->id,
                    "firstName" => $user2->first_name,
                    "lastName" => $user2->last_name,
                    "email" => 'tomas@gmail.com',
                    "address" => null,
                    "created_at" => $user2->created_at,
                    "updated_at" => $user2->updated_at,
                ],
                [
                    "id" => $user3->id,
                    "firstName" => $user3->first_name,
                    "lastName" => $user3->last_name,
                    "email" => 'jonas@gmail.com',
                    "address" => $userDetail->address,
                    "created_at" => $user3->created_at,
                    "updated_at" => $user3->updated_at,
                ]
            ]);
    }

    public function testUserDeleteWillBeSuccessful(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create(['first_name' => 'rokas']);
        $user->createToken('test-token')->plainTextToken;

        $this->actingAs($user)->deleteJson('/api/users/delete/' . $user->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'first_name' => 'rokas',
        ]);
    }
}

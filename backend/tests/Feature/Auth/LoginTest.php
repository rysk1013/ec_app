<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create user
     * @return User
     */
    protected function createUser(): User
    {
        return User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * User can log in successfully with valid credentials
     */
    #[Test]
    public function it_can_log_in_a_user_with_valid_credentials(): void
    {
        $this->createUser();

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('api/login', $loginData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'is_admin',
                    ],
                    'token',
                ],
            ]);

        $this->assertNotNull($response->json('data.token'));
    }

    /**
     * Login fails when required fields are missing
     */
    #[Test]
    public function it_returns_validation_error_if_required_fields_are_missing(): void
    {
        $this->createUser();

        $loginData = [
            'email' => 'test@example.com',
        ];
        $response = $this->postJson('api/login', $loginData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password',
            ]);

        $loginData = [
            'password' => 'password123',
        ];
        $response = $this->postJson('api/login', $loginData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    /**
     * Login fails for an invalid email format
     */
    #[Test]
    public function it_returns_validation_error_for_invalid_email_format(): void
    {
        $this->createUser();

        $loginData = [
            'email' => 'test-example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('api/login', $loginData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    /**
     * Login fails when attempting to log in with an unregistered email address
     */
    #[Test]
    public function it_returns_unauthorized_for_unregistered_email(): void
    {
        $this->createUser();

        $loginData = [
            'email' => 'abc@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('api/login', $loginData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }

    /**
     * Login fails when attempting to log in with a wrong password
     */
    #[Test]
    public function it_returns_unauthorized_for_wrong_password(): void
    {
        $this->createUser();

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrong123',
        ];

        $response = $this->postJson('api/login', $loginData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }
}

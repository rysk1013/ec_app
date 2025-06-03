<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Exceptions\Auth\RegisterFailedException;
use App\Services\AuthService;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can be registered successfully with valid credentials
     */
    #[Test]
    public function it_can_register_a_user_with_valid_credentials(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'is_admin',
                        'created_at',
                        'updated_at',
                    ]
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => 0,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * User registration fails when required fields are missing
     */
    #[Test]
    public function it_returns_validation_error_if_required_fields_are_missing(): void
    {
        // Missing email and password
        $userData = [
            'name' => 'Test User',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
                'password'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);

        // Missing name and password
        $userData = [
            'email' => 'test@example.com',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'password'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);

        // Missing name and email
        $userData = [
            'password' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'name',
                'email'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails for an invalid email format
     */
    #[Test]
    public function it_returns_validation_error_for_invalid_email_format(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test-example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails when the email is already taken
     */
    #[Test]
    public function it_returns_validation_error_if_email_is_already_taken(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails when the password is too short
     */
    #[Test]
    public function it_returns_validation_error_if_password_is_too_short(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ab12',
            'password_confirmation' => 'ab12',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails if the password and confirmation do not match
     */
    #[Test]
    public function it_returns_validation_error_if_password_confirmation_mismatches(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password5678'
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails if something happened
     */
    #[Test]
    public function it_returns_exception_error_if_user_registration_failed(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('storeUser')
            ->andThrows(new RegisterFailedException());

        $this->app->instance(AuthService::class, $mock);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'An unexpected error occurred. Please try again later.',
            ]);
    }
}

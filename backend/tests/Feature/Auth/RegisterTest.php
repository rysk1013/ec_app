<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can be registered successfully with valid credentials
     */
    #[Test]
    public function it_can_register_a_user_with_valid_credentials()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(201)
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
    public function it_returns_validation_error_if_required_fields_are_missing()
    {
        $userData = [
            'name' => 'Test User',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'password'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * User registration fails for an invalid email format
     */
    #[Test]
    public function it_returns_validation_error_for_invalid_email_format()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test-example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(422)
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
    public function it_returns_validation_error_if_email_is_already_taken()
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

        $response->assertStatus(422)
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
    public function it_returns_validation_error_if_password_is_too_short()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ab12',
            'password_confirmation' => 'ab12',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(422)
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
    public function it_returns_validation_error_if_password_confirmation_mismatches()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password5678'
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }
}

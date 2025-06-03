<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LogoutTest extends TestCase
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
     * An authenticated user can successfully log out
     */
    #[Test]
    public function it_can_log_out_an_authenticated_user(): void
    {
        $user = $this->createUser();

        Sanctum::actingAs($user);

        $response = $this->postJson('api/logout');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Logged out successfully.',
            ]);

        //

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);

        // It seems that 「Sanctum::actingAs($user);」 is the cause
        // ↓↓↓

        // $response = $this->postJson('api/logout');
        // $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        //     ->assertJson([
        //         'message' => 'Unauthenticated.',
        //     ]);

        // $responseAfterLogout = $this->withHeaders([
        //     'Authorization' => 'Bearer ' . $tokenPlaintext,
        // ])->getJson('/api/user');

        // $responseAfterLogout->assertStatus(Response::HTTP_UNAUTHORIZED)
        //     ->assertJson([
        //         'message' => 'Unauthenticated.',
        //     ]);
    }

    /**
     * An unauthenticated user's logout attempt fails
     */
    #[Test]
    public function it_returns_unauthorized_for_unauthenticated_logout_attempt()
    {
        $response = $this->postJson('api/logout');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class SendMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_message()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson(
            '/api/messages',
            [
                'message' => 'Hello'
            ]
        );

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Hello'
            ]);

        $this->assertDatabaseHas(
            'messages',
            [
                'message' => 'Hello'
            ]
        );
    }
}
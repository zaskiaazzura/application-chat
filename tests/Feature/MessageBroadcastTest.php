<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class MessageBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_sent_event_is_dispatched()
    {
        Event::fake();

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson(
            '/api/messages',
            [
                'message' => 'Testing'
            ]
        );

        Event::assertDispatched(
            MessageSent::class
        );
    }
}
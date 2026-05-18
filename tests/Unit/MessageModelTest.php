<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_belongs_to_user()
    {
        $user = User::factory()->create();

        $message = Message::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $message->user);
    }
}
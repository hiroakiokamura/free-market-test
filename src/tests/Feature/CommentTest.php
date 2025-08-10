<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/items/{$item->id}/comments", [
            'content' => 'テストコメント'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント'
        ]);
    }

    public function test_guest_user_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/items/{$item->id}/comments", [
            'content' => 'テストコメント'
        ]);

        $response->assertRedirect('/login');
    }

    public function test_comment_content_is_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/items/{$item->id}/comments", [
            'content' => ''
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_comment_content_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $longContent = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/items/{$item->id}/comments", [
            'content' => $longContent
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_validation_message_shown_when_content_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/items/{$item->id}/comments", [
            'content' => ''
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);
    }

    public function test_validation_message_shown_when_content_too_long()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $longContent = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/items/{$item->id}/comments", [
            'content' => $longContent
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントは255文字以内で入力してください']);
    }
} 
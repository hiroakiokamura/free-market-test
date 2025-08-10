<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_like_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/items/{$item->id}/like");

        $response->assertSuccessful();
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_authenticated_user_can_unlike_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        
        // まずいいねする
        $this->actingAs($user)->post("/items/{$item->id}/like");
        
        // 同じエンドポイントで再度リクエストするといいねが解除される
        $response = $this->actingAs($user)->post("/items/{$item->id}/like");

        $response->assertSuccessful();
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_like_item()
    {
        $item = Item::factory()->create();

        $response = $this->post("/items/{$item->id}/like");

        $response->assertRedirect('/login');
    }

    public function test_recommended_items_are_sorted_by_likes()
    {
        $users = User::factory(3)->create();
        $item1 = Item::factory()->create();
        $item2 = Item::factory()->create();
        $item3 = Item::factory()->create();

        // item1に2いいね、item2に1いいね、item3に0いいね
        foreach ($users->take(2) as $user) {
            $this->actingAs($user)->post("/items/{$item1->id}/like");
        }
        $this->actingAs($users->last())->post("/items/{$item2->id}/like");

        $response = $this->get('/?mode=recommended');

        $response->assertSuccessful();
        $response->assertSeeInOrder([$item1->name, $item2->name, $item3->name]);
    }

    public function test_mylist_shows_only_liked_items()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        $notLikedItem = Item::factory()->create(['name' => 'いいねしていない商品']);

        $this->actingAs($user)->post("/items/{$likedItem->id}/like");

        $response = $this->actingAs($user)->get('/?mode=mylist');

        $response->assertSuccessful();
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }
} 
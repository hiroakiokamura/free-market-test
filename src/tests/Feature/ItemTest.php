<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        $items = Item::factory(3)->create();

        $response = $this->get('/');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_purchased_item_shows_sold_status()
    {
        $item = Item::factory()->create(['status' => 'sold']);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_unauthorized_user_cannot_create_item()
    {
        $response = $this->get('/sell');

        $response->assertRedirect('/login');
    }

    public function test_mylist_shows_only_user_liked_items()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        $otherItem = Item::factory()->create(['name' => 'いいねしていない商品']);

        // いいねを追加
        $this->actingAs($user);
        $this->post("/items/{$likedItem->id}/like");

        $response = $this->get('/mylist');

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_search_items_by_keyword()
    {
        $item1 = Item::factory()->create(['name' => 'テスト商品A']);
        $item2 = Item::factory()->create(['name' => '別の商品B']);

        $response = $this->get('/?search=テスト');

        $response->assertSee('テスト商品A');
        $response->assertDontSee('別の商品B');
    }

    public function test_search_shows_no_results_message()
    {
        Item::factory()->create(['name' => '商品A']);

        $response = $this->get('/?search=存在しない商品名');

        $response->assertSee('検索結果が見つかりません');
    }

    public function test_recommended_items_are_sorted_by_likes()
    {
        $users = User::factory(3)->create();
        $item1 = Item::factory()->create(['name' => '人気商品']);
        $item2 = Item::factory()->create(['name' => '普通の商品']);

        // item1に2いいね、item2に1いいね
        foreach ($users->take(2) as $user) {
            $this->actingAs($user)->post("/items/{$item1->id}/like");
        }
        $this->actingAs($users->last())->post("/items/{$item2->id}/like");

        $response = $this->get('/?mode=recommended');

        $response->assertSeeInOrder(['人気商品', '普通の商品']);
    }

    public function test_item_detail_shows_all_information()
    {
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new'
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee([
            'テスト商品',
            '商品の説明',
            '1,000',
            '新品、未使用'
        ]);
    }
} 
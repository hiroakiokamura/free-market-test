<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_page_requires_authentication()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_purchase_page()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($buyer)->get("/purchase/{$item->id}");
        $response->assertStatus(200);
    }

    public function test_user_cannot_purchase_own_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(403);
    }

    public function test_user_can_update_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'address' => '1-1-1',
            'building' => 'テストビル101',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'address' => '1-1-1',
            'building' => 'テストビル101',
        ]);
    }

    public function test_validation_error_when_shipping_address_fields_are_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '',
            'prefecture' => '',
            'city' => '',
            'address' => '',
        ]);

        $response->assertSessionHasErrors(['postal_code', 'prefecture', 'city', 'address']);
    }

    public function test_user_can_complete_purchase()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'address' => '1-1-1',
        ]);
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'price' => 1000,
            'status' => 'on_sale',
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => 1000,
            'shipping_postal_code' => '1234567',
            'shipping_prefecture' => '東京都',
            'shipping_city' => '渋谷区',
            'shipping_address' => '1-1-1',
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold_out',
        ]);
    }
} 
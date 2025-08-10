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

    public function test_user_can_purchase_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1'
        ]);
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'price' => 1000,
            'status' => 'on_sale'
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => 1000,
            'shipping_postal_code' => '1234567',
            'shipping_address' => '東京都渋谷区1-1-1'
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold'
        ]);
    }

    public function test_user_cannot_purchase_own_item()
    {
        $user = User::factory()->create([
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1',
            'email_verified_at' => now()
        ]);
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_purchase_sold_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'sold'
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}");

        $response->assertStatus(403);
    }

    public function test_user_must_set_shipping_address_before_purchase()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => null,
            'address' => null
        ]);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}");

        $response->assertRedirect()->assertSessionHas('error', '配送先住所を設定してください');
    }

    public function test_user_can_update_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1',
            'building' => null
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1'
        ]);
    }

    public function test_shipping_address_validation()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '',
            'address' => ''
        ]);

        $response->assertSessionHasErrors(['postal_code', 'address']);
    }
} 
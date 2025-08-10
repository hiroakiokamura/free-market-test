<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_item()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new',
            'user_id' => $user->id
        ]);
    }

    public function test_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => '',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '',
            'price' => 1000,
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_price_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => '',
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_image_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => ''
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_price_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 'not-a-number',
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_image_must_be_valid_type()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明',
            'price' => 1000,
            'condition' => 'new',
            'category_ids' => ['カテゴリ1'],
            'image' => UploadedFile::fake()->create('test.txt')
        ]);

        $response->assertSessionHasErrors('image');
    }
} 
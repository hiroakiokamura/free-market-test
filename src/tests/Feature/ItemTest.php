<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_list_page_can_be_rendered()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_mylist_page_requires_authentication()
    {
        $response = $this->get('/mylist');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_mylist()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/mylist');
        $response->assertStatus(200);
    }

    public function test_item_detail_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
    }

    public function test_user_can_create_item()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'Test Item',
            'description' => 'Test Description',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'description' => 'Test Description',
            'price' => 1000,
            'user_id' => $user->id,
        ]);
        Storage::disk('public')->assertExists('items/' . Item::latest()->first()->image_path);
    }

    public function test_validation_error_when_required_fields_are_empty()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => '',
            'description' => '',
            'price' => '',
            'image' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'description', 'price', 'image']);
    }

    public function test_only_authenticated_users_can_create_items()
    {
        $response = $this->post('/sell', [
            'name' => 'Test Item',
            'description' => 'Test Description',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect('/login');
    }
} 
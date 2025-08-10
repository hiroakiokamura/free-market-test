<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word . 'の商品',
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 100000),
            'image_path' => 'items/dummy1.jpg',
            'condition' => $this->faker->randomElement(['新品', '良好', '傷あり']),
            'status' => 'on_sale',
        ];
    }
} 
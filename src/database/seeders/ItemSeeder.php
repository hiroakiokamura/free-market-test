<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // ダミーユーザーを作成（存在しない場合）
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // 商品データを定義
        $items = [
            [
                'name' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'description' => '洗練されたデザインの高級感のある腕時計です。',
                'image_path' => 'items/dummy1.jpg',
                'condition' => '良好'
            ],
            [
                'name' => '高速で信頼性の高いHDD',
                'price' => 5000,
                'description' => '大容量で信頼性の高いハードディスクです。',
                'image_path' => 'items/dummy2.jpg',
                'condition' => '目立った傷や汚れなし'
            ],
            [
                'name' => '新鮮な石ひき3種のセット',
                'price' => 300,
                'description' => '厳選された3種類の石ひきセットです。',
                'image_path' => 'items/dummy3.jpg',
                'condition' => 'やや傷や汚れあり'
            ],
            [
                'name' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'description' => '上質な革を使用したクラシックな革靴です。',
                'image_path' => 'items/dummy4.jpg',
                'condition' => '状態が悪い'
            ],
            [
                'name' => '高性能ノートパソコン',
                'price' => 45000,
                'description' => '最新のスペックを搭載したノートパソコンです。',
                'image_path' => 'items/dummy5.jpg',
                'condition' => '良好'
            ],
            [
                'name' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'description' => 'クリアな音質を実現するマイクです。',
                'image_path' => 'items/dummy6.jpg',
                'condition' => '目立った傷や汚れなし'
            ],
            [
                'name' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'description' => 'トレンド感のあるデザインのショルダーバッグです。',
                'image_path' => 'items/dummy7.jpg',
                'condition' => 'やや傷や汚れあり'
            ],
            [
                'name' => '使いやすいタンブラー',
                'price' => 500,
                'description' => '保温・保冷機能付きの便利なタンブラーです。',
                'image_path' => 'items/dummy8.jpg',
                'condition' => '状態が悪い'
            ],
            [
                'name' => '手挽のコーヒーミル',
                'price' => 4000,
                'description' => '本格的な挽きたての香りを楽しめるコーヒーミルです。',
                'image_path' => 'items/dummy9.jpg',
                'condition' => '良好'
            ],
            [
                'name' => '便利なメイクアップセット',
                'price' => 2500,
                'description' => '基本的なメイクアイテムが揃ったセットです。',
                'image_path' => 'items/dummy10.jpg',
                'condition' => '目立った傷や汚れなし'
            ]
        ];

        // 商品データを順番に作成
        foreach ($items as $item) {
            Item::create(array_merge($item, ['user_id' => $user->id]));
        }
    }
} 
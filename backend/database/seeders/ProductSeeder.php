<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'スリム冷蔵庫 (200L)',
                'description' => '省エネ設計のスリムな冷蔵庫。一人暮らしに最適。',
                'price' => 55000,
                'stock' => 15,
                'image_url' => '/images/fridge_slim.jpg',
                'category_id' => 1, // 家電
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ドラム式洗濯機 (10kg)',
                'description' => '温水洗浄機能付き。洗濯から乾燥までこれ一台。',
                'price' => 120000,
                'stock' => 8,
                'image_url' => '/images/washer_drum.jpg',
                'category_id' => 1, // 家電
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '4K有機ELテレビ (55インチ)',
                'description' => '鮮やかな映像美。リビングでの映画鑑賞に。',
                'price' => 180000,
                'stock' => 5,
                'image_url' => '/images/tv_oled.jpg',
                'category_id' => 1, // 家電
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ファンタジー小説「星の雫」上',
                'description' => '壮大な世界観で描かれる冒険物語の序章。',
                'price' => 880,
                'stock' => 120,
                'image_url' => '/images/book_hoshinoshizuku_1.jpg',
                'category_id' => 2, // 本
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ビジネス戦略の教科書',
                'description' => '最新のビジネスモデルを学ぶための入門書。',
                'price' => 2500,
                'stock' => 75,
                'image_url' => '/images/book_biz_strategy.jpg',
                'category_id' => 2, // 本
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '北欧風3人掛けソファ',
                'description' => '温かみのある木製フレームと肌触りの良い生地。',
                'price' => 78000,
                'stock' => 10,
                'image_url' => '/images/sofa_nordic.jpg',
                'category_id' => 3, // 家具
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '折りたたみダイニングテーブル',
                'description' => 'コンパクトに収納可能。一人暮らしや来客時に便利。',
                'price' => 25000,
                'stock' => 22,
                'image_url' => '/images/table_folding.jpg',
                'category_id' => 3, // 家具
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '北海道産じゃがいも (5kg)',
                'description' => 'ホクホクとした食感が特徴。煮込み料理に最適。',
                'price' => 1200,
                'stock' => 200,
                'image_url' => '/images/potato_hokkaido.jpg',
                'category_id' => 4, // 食品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '有機栽培トマト (1kg)',
                'description' => '豊かな甘みと酸味のバランスがとれた完熟トマト。',
                'price' => 850,
                'stock' => 150,
                'image_url' => '/images/tomato_organic.jpg',
                'category_id' => 4, // 食品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'カジュアルデニムジャケット',
                'description' => '季節を問わず着用できる定番アイテム。ユニセックス。',
                'price' => 7900,
                'stock' => 50,
                'image_url' => '/images/jacket_denim.jpg',
                'category_id' => 5, // 衣料品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '速乾性Tシャツ (ランニング用)',
                'description' => '吸湿速乾性に優れた素材で、運動時も快適。',
                'price' => 3200,
                'stock' => 80,
                'image_url' => '/images/tshirt_running.jpg',
                'category_id' => 5, // 衣料品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ランニングシューズ (メンズ)',
                'description' => '軽量でクッション性に優れた高機能シューズ。',
                'price' => 9800,
                'stock' => 30,
                'image_url' => '/images/shoes_running_men.jpg',
                'category_id' => 6, // スポーツ用品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ヨガマット (厚手タイプ)',
                'description' => 'クッション性が高く、自宅でのヨガに最適。',
                'price' => 2800,
                'stock' => 45,
                'image_url' => '/images/yoga_mat.jpg',
                'category_id' => 6, // スポーツ用品
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '最新RPGゲーム (PS5版)',
                'description' => '圧倒的なグラフィックで描かれるファンタジーRPG。',
                'price' => 8900,
                'stock' => 60,
                'image_url' => '/images/game_rpg_ps5.jpg',
                'category_id' => 7, // ゲーム
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '知育ブロックセット (1000ピース)',
                'description' => '想像力を育むカラフルなブロック。対象年齢3歳～。',
                'price' => 4500,
                'stock' => 90,
                'image_url' => '/images/toy_blocks.jpg',
                'category_id' => 8, // おもちゃ
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ドローン (初心者向け)',
                'description' => '簡単操作で空撮が楽しめる小型ドローン。',
                'price' => 15000,
                'stock' => 18,
                'image_url' => '/images/drone_beginner.jpg',
                'category_id' => 8, // おもちゃ
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => '高保湿美容液 (30ml)',
                'description' => '乾燥肌に潤いを与える、人気No.1美容液。',
                'price' => 6800,
                'stock' => 70,
                'image_url' => '/images/serum_moisture.jpg',
                'category_id' => 9, // 美容
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'UVカット日焼け止め (SPF50+)',
                'description' => 'ウォータープルーフでレジャーにも最適。',
                'price' => 2200,
                'stock' => 110,
                'image_url' => '/images/sunscreen_uv.jpg',
                'category_id' => 9, // 美容
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ノートPC (Core i7 / 16GB RAM)',
                'description' => '高性能CPU搭載で、ビジネスもプライベートも快適。',
                'price' => 135000,
                'stock' => 12,
                'image_url' => '/images/laptop_i7.jpg',
                'category_id' => 10, // パソコン・周辺機器
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
            [
                'name' => 'ワイヤレスマウス (静音タイプ)',
                'description' => 'カフェなどでも周りを気にせず使える静音設計。',
                'price' => 2800,
                'stock' => 65,
                'image_url' => '/images/mouse_wireless.jpg',
                'category_id' => 10, // パソコン・周辺機器
                'created_at' => '2025-06-06 01:19:05',
                'updated_at' => '2025-06-06 01:19:05',
            ],
        ];

        Product::insert($products);
    }
}

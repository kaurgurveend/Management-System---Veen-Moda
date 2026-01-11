<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KainSeeder extends Seeder
{
    public function run()
    {
        // Wipe existing products and variants to refresh dataset to match screenshots
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ensure needed categories exist
        $categories = ['Semi Prancis', 'Santilli', 'Brokat'];
        $categoryIds = [];
        foreach ($categories as $c) {
            $categoryIds[$c] = DB::table('categories')->where('name', $c)->value('id');
            if (!$categoryIds[$c]) {
                $categoryIds[$c] = DB::table('categories')->insertGetId([
                    'name' => $c,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Products + variants based on your screenshots (expanded)
        $products = [
            [
                'category' => 'Semi Prancis',
                'name' => 'French lace',
                'price' => 200000,
                'variants' => [
                    ['color' => 'PINK', 'stock' => 11],
                    ['color' => 'PEACH', 'stock' => 9],
                    ['color' => 'LILAC', 'stock' => 12],
                    ['color' => 'LEMON PUCUK', 'stock' => 11],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Jablai',
                'price' => 80000,
                'variants' => [
                    ['color' => 'MARUN', 'stock' => 37]
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Maurer',
                'price' => 100000,
                'variants' => [
                    ['color' => 'TEMBAGA', 'stock' => 11],
                    ['color' => 'GOLD', 'stock' => 19],
                    ['color' => 'ABU', 'stock' => 9],
                    ['color' => 'P.TULANG', 'stock' => 17],
                    ['color' => 'P.BERSIH', 'stock' => 15],
                    ['color' => 'KUNING', 'stock' => 6],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Zabra',
                'price' => 275000,
                'variants' => [
                    ['color' => 'DENIM', 'stock' => 7],
                    ['color' => 'DONGKER', 'stock' => 15],
                    ['color' => 'KRIM', 'stock' => 6],
                    ['color' => 'PUCUK PISANG', 'stock' => 10],
                    ['color' => 'UNGU', 'stock' => 15],
                    ['color' => 'OLIV', 'stock' => 5],
                    ['color' => 'SAGE', 'stock' => 10],
                ]
            ],
            [
                'category' => 'Santilli',
                'name' => 'Santili Asyok',
                'price' => 75000,
                'variants' => [
                    ['color' => 'CREAM', 'stock' => 5],
                    ['color' => 'P. BERSIH', 'stock' => 2],
                    ['color' => 'P. TULANG', 'stock' => 10],
                    ['color' => 'PEACH', 'stock' => 11],
                    ['color' => 'SALEM', 'stock' => 23],
                    ['color' => 'HITAM', 'stock' => 44],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Montisa',
                'price' => 125000,
                'variants' => [
                    ['color' => 'WARDA', 'stock' => 2],
                    ['color' => 'ARMY', 'stock' => 10],
                    ['color' => 'ABU', 'stock' => 16],
                    ['color' => 'SALEM', 'stock' => 8],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Saint Laurent',
                'price' => 275000,
                'variants' => [
                    ['color' => 'PINK/FUSIA', 'stock' => 3],
                    ['color' => 'LILAC', 'stock' => 4],
                    ['color' => 'ANGGUR', 'stock' => 4],
                    ['color' => 'BIRU ELEKTRIK', 'stock' => 2],
                    ['color' => 'PUCUK', 'stock' => 2],
                    ['color' => 'LEMON', 'stock' => 4],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Gleam lace',
                'price' => 200000,
                'variants' => [
                    ['color' => 'KUNING LEMON', 'stock' => 8],
                    ['color' => 'PEACH', 'stock' => 8],
                    ['color' => 'PINK', 'stock' => 7],
                    ['color' => 'PURPEL', 'stock' => 5],
                    ['color' => 'SAGE', 'stock' => 1],
                    ['color' => 'TEMBAGA', 'stock' => 3],
                    ['color' => 'BABY BLUE', 'stock' => 2],
                ]
            ],
            // Additional products from other screenshots
            [
                'category' => 'Semi Prancis',
                'name' => 'Glossy Lace',
                'price' => 200000,
                'variants' => [
                    ['color' => 'PUTIH', 'stock' => 18], ['color' => 'MAGENTA', 'stock' => 16],
                    ['color' => 'AQUA GRAY', 'stock' => 16], ['color' => 'EMERALD BLUE', 'stock' => 11],
                    ['color' => 'OLIVE', 'stock' => 20], ['color' => 'MILO', 'stock' => 17],
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Horas',
                'price' => 150000,
                'variants' => [
                    ['color' => 'PINK', 'stock' => 11], ['color' => 'UNGU / ANGGUR', 'stock' => 15],
                    ['color' => 'BABY BLUE', 'stock' => 7], ['color' => 'COKLAT', 'stock' => 12],
                    ['color' => 'LILAC', 'stock' => 4], ['color' => 'OTAK UDANG', 'stock' => 14]
                ]
            ],
            [
                'category' => 'Brokat',
                'name' => 'Brokat Cloe',
                'price' => 350000,
                'variants' => [
                    ['color' => 'BIRU WARDAH', 'stock' => 9], ['color' => 'WINE/MAGENTA', 'stock' => 4],
                    ['color' => 'TARO', 'stock' => 4], ['color' => 'MUSTARD', 'stock' => 6],
                    ['color' => 'BIRU ELEKTRIK', 'stock' => 5], ['color' => 'MACHA', 'stock' => 5],
                    ['color' => 'OREN WORTEL', 'stock' => 9], ['color' => 'SAGE GREEN', 'stock' => 5]
                ]
            ],
            [
                'category' => 'Brokat',
                'name' => 'Brokat Frida',
                'price' => 100000,
                'variants' => [
                    ['color' => 'KUNING', 'stock' => 12], ['color' => 'OREN', 'stock' => 22],
                    ['color' => 'TARO', 'stock' => 1], ['color' => 'ABU', 'stock' => 23],
                    ['color' => 'HIJAU BOTOL', 'stock' => 7], ['color' => 'MAGENTA', 'stock' => 8],
                    ['color' => 'SALEM', 'stock' => 3]
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'New JB Eye',
                'price' => 170000,
                'variants' => [
                    ['color' => 'ROSE GOLD', 'stock' => 9], ['color' => 'GREEN TEA', 'stock' => 6],
                    ['color' => 'CARAMEL', 'stock' => 2], ['color' => 'TARO / MOUVE', 'stock' => 4],
                    ['color' => 'TAN', 'stock' => 8], ['color' => 'PEACH', 'stock' => 2]
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'JB Eye',
                'price' => 170000,
                'variants' => [
                    ['color' => 'WHITE / SILVER', 'stock' => 7]
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'JB Dove',
                'price' => 150000,
                'variants' => [
                    ['color' => 'PEACH', 'stock' => 7], ['color' => 'ABU-ABU', 'stock' => 12]
                ]
            ],
            [
                'category' => 'Semi Prancis',
                'name' => 'Asyok Cord - Mr.Super',
                'price' => 150000,
                'variants' => [
                    ['color' => 'OLIVE', 'stock' => 7], ['color' => 'BLONDE', 'stock' => 2]
                ]
            ],
        ];

        foreach ($products as $p) {
            $catId = $categoryIds[$p['category']] ?? $categoryIds['Brokat'];
            $productId = DB::table('products')->insertGetId([
                'category_id' => $catId,
                'name' => $p['name'],
                'price' => $p['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($p['variants'] as $v) {
                DB::table('product_variants')->insert([
                    'product_id' => $productId,
                    'color' => $v['color'],
                    'stock' => $v['stock'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
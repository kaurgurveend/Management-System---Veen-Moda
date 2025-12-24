<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Penting: Import Model Category

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Santilli'],
            ['name' => 'Semi Prancis'],
            ['name' => 'Songket'],
            ['name' => 'Brokat'],
            ['name' => 'Satin'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
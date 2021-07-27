<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Sports', 'Gaming', 'Drugs', 'Lauch Food', 'Eving Food', 'Moring Food'];
        foreach ($categories as $category) {
            Category::firstOrcreate([
                'name' => $category
            ]);
        }
    }
}

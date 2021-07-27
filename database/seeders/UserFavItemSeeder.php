<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\UserFavItem;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserFavItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 500; $i++) {
            UserFavItem::firstOrcreate([
                'user_id' => User::inRandomOrder()->first()->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'name' => $faker->word(),
                'price' => rand(1000, 20000)
            ]);
        }
    }
}

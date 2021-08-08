<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $user = User::firstOrcreate([
                'name' => $faker->firstName() . " " . $faker->firstName(),
                'email' => $faker->email(),
                'password' => bcrypt('password'),
                'gender' => rand(1, 3),
                'image' => '/uploads/profile/no_image.png',
                'qrcode' => '/uploads/qrcodes/user/abdulbasit-ssds.png',
                'birthday' => $faker->date('Y/m/d')
            ]);

            if ($i < 15) {
                continue;
            }

            $users_id = User::inRandomOrder()->take(rand(1, 15))->pluck('id')->toArray();
            $user->friends()->attach($users_id);
        }
    }
}

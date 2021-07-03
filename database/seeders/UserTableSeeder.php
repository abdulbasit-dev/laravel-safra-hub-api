<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    User::create([
      'name' => 'basit',
      'email' => 'basit@test.com',
      'password' => bcrypt('password'),
    ]);
    User::create([
      'name' => 'hama',
      'email' => 'hama@test.com',
      'password' => bcrypt('password'),
    ]);
    User::create([
      'name' => '7hama',
      'email' => '7hama@test.com',
      'password' => bcrypt('password'),
    ]);
    User::create([
      'name' => 'basit',
      'email' => 'bassdsi2t@test.com',
      'password' => bcrypt('password'),
    ]);
    User::create([
      'name' => 'hama',
      'email' => 'hamsdda@test.com',
      'password' => bcrypt('password'),
    ]);
    User::create([
      'name' => '7hama',
      'email' => '7heasdma@test.com',
      'password' => bcrypt('password'),
    ]);
  }
}

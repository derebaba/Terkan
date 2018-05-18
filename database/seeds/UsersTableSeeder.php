<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder{

    public function run()
    {
        $faker = Faker::create();
        factory(App\Models\User::class, 50)->create();
    }
}
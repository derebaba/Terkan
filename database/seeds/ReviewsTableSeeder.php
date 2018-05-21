<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$faker = Faker::create();

		foreach(range(1,100) as $index){
			App\Models\Review::create([
				'stars' => mt_rand(1,5), 
				'body' => $faker->text(191), 
				'user_id' => $faker->numberBetween(1, 50),
				'reviewable_id' => $faker->numberBetween(63174, 63185),
				'reviewable_type' => 'tv',
			]);
		}

		foreach(range(1,100) as $index){
			App\Models\Review::create([
				'stars' => mt_rand(1,5), 
				'body' => $faker->text(191), 
				'user_id' => $faker->numberBetween(1, 50),
				'reviewable_id' => $faker->numberBetween(11, 20),
				'reviewable_type' => 'movie',
			]);
		}
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// $this->call(UsersTableSeeder::class);
		factory(App\Models\User::class, 25)->create()->each(function ($u) {
			$u->reviews()->save(factory(App\Review::class)->states('tv')->make());
		});

		factory(App\Models\User::class, 25)->create()->each(function ($u) {
			$u->reviews()->save(factory(App\Review::class)->states('movie')->make());
		});
    }
}

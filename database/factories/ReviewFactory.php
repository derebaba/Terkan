<?php

use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    return [
		'stars' => mt_rand(1,5), 
		'body' => $faker->text(191), 
		'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});

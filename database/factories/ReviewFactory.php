<?php

use Faker\Generator as Faker;

$factory->state(App\Review::class, 'movie', [
	'reviewable_id' => mt_rand(11, 20),
    'reviewable_type' => 'movie',
]);

$factory->state(App\Review::class, 'tv', [
	'reviewable_id' => mt_rand(63174, 63185),
    'reviewable_type' => 'tv',
]);

$factory->define(App\Review::class, function (Faker $faker) {
    return [
		'stars' => mt_rand(1,5), 
		'body' => $faker->text(191), 
		'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});

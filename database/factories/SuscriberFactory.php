<?php

use Faker\Generator as Faker;

$factory->define(App\Subscriber::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});

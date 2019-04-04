<?php

use Faker\Generator as Faker;

$factory->define(App\E_mail::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 50),
        'subscribers' => serialize([1, 2, 3, 4, 5, 6, 7, 11, 12, 13, 15, 17, 18, 19, 21, 22, 23, 24, 25, 26, 27]),
        'content' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'Title' => $faker->name,
    ];
});

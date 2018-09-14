<?php

use Faker\Generator as Faker;

$factory->define(App\Shipment::class, function (Faker $faker) {
    return [
        'user_id' => Auth::id(),
        'sender_name' => $faker->name,
        'sender_email' => $faker->unique()->safeEmail,
        'sender_address' => $faker->address,
        'sender_city' => $faker->city,
        'sender_phone' => $faker->randomNumber($nbDigits = NULL, $strict = false),
        'client_name' => $faker->name,
        'client_email' => $faker->unique()->safeEmail,
        'client_address' => $faker->address,
        'client_city' => $faker->city,
        'client_phone' => $faker->randomNumber($nbDigits = NULL, $strict = false),
        'assign_staff' => $faker->name,
        'airway_bill_no' => $faker->randomDigit,
        'bar_code' => $faker->randomDigit,
        'amount_ordered' => $faker->randomDigit,
        'shipment_id' => $faker->randomDigit,
        'paid' => '123',
        'driver' => $faker->name,
        'payment' => 'yes',
        'shipment_type' => $faker->name,
        'insuarance_status' => 'yes',
        // 'booking_date' => '2018-08-08',
        // 'derivery_date' => '2018-08-08',
        'derivery_time' => $faker->time($format = 'H:i:s', $max = 'now'),
        'remark' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'derivery_status' => 'cancled',
    ];
});

<?php

$factory->define(App\Device::class, function (Faker\Generator $faker) {    

    return [
        'name' => $faker->word,
        'imei' => $faker->unique()->randomNumber($nbDigits = 8),
        'longitude' =>  $faker->longitude(),
        'latitude' => $faker->latitude(),
    ];
});
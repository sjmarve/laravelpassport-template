<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;
    
    $gender = ['m', 'f'][rand(0,1)];
    return [
        'firstname' => $faker->firstName($gender),
        'lastname' => $faker->lastName($gender),
        'email' => $faker->unique()->safeEmail,
        'cell' => function() use($faker){
        	return $faker->unique()->phoneNumber;
        },
        'gender' => $gender,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

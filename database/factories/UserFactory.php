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

$factory->define(App\Models\User::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'birthday'=> $faker->date,
        'gender'=> rand(0,1),
        'phone'=> rand(100000000,999999999),
        'address'=>$faker->address,
        'role_id'=> 5,
        'actived'=>0,
        'banned'=>1,
        'remember_token' => str_random(10),
    ];
});

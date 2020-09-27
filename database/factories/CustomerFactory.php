<?php

namespace Database\Factories;

use Faker\Generator;
use App\Entities\Customer;
use Services\Customer\Enums\GenderEnum;

/** @var \LaravelDoctrine\ORM\Testing\Factory $factory */
$factory->define(Customer::class, function (Generator $faker, array $attributes = []) {
    return [
        'first_name' => $attributes['first_name'] ?? $faker->firstName,
        'last_name' => $attributes['last_name'] ?? $faker->lastName,
        'username' => $attributes['username'] ?? $faker->userName,
        'gender' => $attributes['gender'] ?? $faker->randomElement([GenderEnum::MALE()->getValue(), GenderEnum::FEMALE()->getValue()]),
        'country' => $attributes['country'] ?? $faker->country,
        'city' => $attributes['city'] ?? $faker->city,
        'phone' => $attributes['phone'] ?? $faker->phoneNumber,
        'password' => $attributes['password'] ?? $faker->md5,
        'email' => $attributes['email'] ?? $faker->unique()->email,
    ];
});

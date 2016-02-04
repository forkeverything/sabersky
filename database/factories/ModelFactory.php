<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'company_id' => 1,
        'role_id' => $faker->numberBetween(1,6)
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word . ' ' . $faker->numberBetween(0, 200) . ' MW',
        'location' => $faker->city,
        'description' => $faker->paragraph(5),
        'company_id' => 1
    ];
});

$factory->define(App\Vendor::class, function (Faker\Generator $faker) {
    return [
        'name' => 'PT.' . $faker->company,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'bank_name' => $faker->randomElement(['BNI', 'Maybank', 'BCA', 'BRI', 'HSBC']),
        'bank_account_name' => $faker->name,
        'bank_account_number' => $faker->randomNumber(8),
        'company_id' => 1
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'specification' => $faker->paragraph(2),
        'project_id' => factory(App\Project::class)->create()->id
    ];
});

$factory->define(App\PurchaseRequest::class, function (Faker\Generator $faker) {
    return [
        'quantity' => $faker->randomDigitNotNull,
        'due' => $faker->dateTimeThisYear,
        'urgent' => $faker->boolean(20),
        'state' => $faker->randomElement(['open', 'cancelled']),
        'item_id' => factory(App\Item::class)->create()->id,
        'project_id' => factory(App\Project::class)->create()->id,
        'user_id' => factory(App\User::class)->create([
            'role_id' => 2
        ])->id
    ];
});

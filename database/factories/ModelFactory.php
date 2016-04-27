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

use \App\Company;
use \App\Item;
use \App\Project;

$factory->define(\App\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->paragraph(5)
    ];
});
$factory->define(\App\Role::class, function (Faker\Generator $faker) {
    return [
        'position' => $faker->word,
        'company_id' => 1
    ];
});

$factory->define(\App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'company_id' => factory(Company::class)->create()->id,
        'role_id' => factory(\App\Role::class)->create()->id
    ];
});

$factory->define(\App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word . ' ' . $faker->numberBetween(0, 200) . ' MW',
        'location' => $faker->city,
        'description' => $faker->paragraph(5),
        'company_id' => factory(Company::class)->create()->id
    ];
});

$factory->define(\App\Vendor::class, function (Faker\Generator $faker) {
    return [
        'name' => 'PT.' . $faker->company,
        'description' => $faker->paragraph(3),
        'base_company_id' => factory(Company::class)->create()->id,
        'linked_company_id' => factory(Company::class)->create()->id,
    ];
});

$factory->define(\App\BankAccount::class, function (Faker\Generator $faker) {
    return [
        'bank_name' => 'Bank ' . $faker->company,
        'account_name' => $faker->name,
        'account_number' => $faker->randomNumber(9),
        'bank_phone' => $faker->phoneNumber,
        'bank_address' => $faker->address,
        'swift' => str_random(5),
        'vendor_id' => factory(\App\Vendor::class)->create()->id
    ];
});

$factory->define(\App\Address::class, function (Faker\Generator $faker) {
    return [
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->city,
        'zip' => $faker->postcode,
        'phone' => $faker->phoneNumber,
        'country_id' => $faker->randomElement(\App\Country::all()->toArray())['id'],
        'owner_id' => factory(\App\Vendor::class)->create()->id,
        'owner_type' => 'vendor'
    ];
});

$factory->define(\App\PurchaseOrder::class, function (Faker\Generator $faker) {
    return [
        'project_id' => 1,
        'vendor_id' => factory(\App\Vendor::class)->create()->id,
        'user_id' => factory(\App\User::class)->create()->id
    ];
});

$factory->define(\App\Item::class, function (Faker\Generator $faker) {
    return [
        'sku' => str_random(10),
        'brand' => $faker->name,
        'name' => $faker->word,
        'specification' => $faker->paragraph(2),
        'company_id' => factory(Company::class)->create()->id
    ];
});

$factory->define(\App\PurchaseRequest::class, function (Faker\Generator $faker) {
    $project = factory(Project::class)->create();
    return [
        'quantity' => $faker->numberBetween(1, 50),                 // numberBetween is inclusive. Override to 0 to make 'completed' PRs
        'due' => $faker->dateTimeThisYear->format('d/m/Y'),
        'state' => $faker->randomElement(['open', 'cancelled']),
        'urgent' => $faker->boolean(20),
        'project_id' => $project->id,
        'item_id' => factory(Item::class)->create([
            'company_id' => $project->company->id
        ])->id,
        'user_id' => factory(\App\User::class)->create([
            'company_id' => $project->company->id
        ])->id
    ];
});

$factory->define(\App\Photo::class, function (Faker\Generator $faker) {
    $name = $faker->word;
    return [
        'name' => $name . '.jpg',
        'path' => 'uploads/test/' . $name . '.jpg',
        'thumbnail_path' => 'uploads/test/tn_' . $name . '.jpg'
    ];
});


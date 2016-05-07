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

use App\Address;
use App\BankAccount;
use \App\Company;
use \App\Item;
use App\LineItem;
use App\Photo;
use \App\Project;
use App\PurchaseOrder;
use App\PurchaseOrderAdditionalCost;
use App\PurchaseRequest;
use App\Role;
use App\Rule;
use App\User;
use App\Vendor;

$factory->define(Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->paragraph(5)
    ];
});

$factory->define(Rule::class, function (Faker\Generator $faker) {
    $allProperties = DB::table('rule_properties')->select('*')->get();
    $selectedProperty = $faker->randomElement($allProperties);
    $associatedTriggers =  DB::table('rule_triggers')->select('*')->where('rule_property_id', $selectedProperty->id)->get();
    $selectedTrigger = $faker->randomElement($associatedTriggers);
    $limit = null;
    if ($selectedTrigger->has_limit) $limit = $selectedTrigger->limit_type == 'percentage' ? $faker->numberBetween(0, 100) : $faker->randomFloat(2, 0, 10000);
    return [
        'rule_property_id' => $selectedProperty->id,
        'rule_trigger_id' => $selectedTrigger->id,
        'limit' => $limit,
        'company_id' => factory(\App\Company::class)->create()->id
    ];
});

$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'position' => $faker->word,
        'company_id' => 1
    ];
});

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'company_id' => factory(Company::class)->create()->id,
        'role_id' => factory(Role::class)->create()->id
    ];
});

$factory->define(Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word . ' ' . $faker->numberBetween(0, 200) . ' MW',
        'location' => $faker->city,
        'description' => $faker->paragraph(5),
        'company_id' => factory(Company::class)->create()->id
    ];
});

$factory->define(Vendor::class, function (Faker\Generator $faker) {
    return [
        'name' => 'PT.' . $faker->company,
        'description' => $faker->paragraph(3),
        'base_company_id' => factory(Company::class)->create()->id,
        'linked_company_id' => factory(Company::class)->create()->id,
    ];
});

$factory->define(BankAccount::class, function (Faker\Generator $faker) {
    return [
        'bank_name' => 'Bank ' . $faker->company,
        'account_name' => $faker->name,
        'account_number' => $faker->randomNumber(9),
        'bank_phone' => $faker->phoneNumber,
        'bank_address' => $faker->address,
        'swift' => str_random(5),
        'vendor_id' => factory(Vendor::class)->create()->id
    ];
});

$factory->define(Address::class, function (Faker\Generator $faker) {
    return [
        'contact_person' => $faker->name,
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->name . ' Building',
        'city' => $faker->city,
        'state' => $faker->city,
        'zip' => $faker->postcode,
        'phone' => $faker->phoneNumber,
        'country_id' => $faker->randomElement(\App\Country::all()->toArray())['id'],
        'owner_id' => factory(Vendor::class)->create()->id,
        'owner_type' => 'vendor'
    ];
});

$factory->define(PurchaseRequest::class, function (Faker\Generator $faker) {
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
        'user_id' => factory(User::class)->create([
            'company_id' => $project->company->id
        ])->id
    ];
});

$factory->define(PurchaseOrder::class, function (Faker\Generator $faker) {
    $vendor = factory(Vendor::class)->create();
    $vendorAddress = factory(Address::class)->create([
        'owner_id' => $vendor->id
    ]);
    $vendorBankAccount = factory(BankAccount::class)->create([
        'vendor_id' => $vendor->id
    ]);
    return [
        'vendor_id' => $vendor->id,
        'vendor_address_id' => $vendorAddress->id,
        'vendor_bank_account_id' => $vendorBankAccount->id,
        'user_id' => factory(User::class)->create([
            'company_id' => $vendor->base_company_id
        ])->id,
        'company_id' => $vendor->base_company_id
    ];
});

$factory->define(PurchaseOrderAdditionalCost::class, function (Faker\Generator $faker) {
   $type = $faker->randomElement(['fixed', '%']);
    $amount = $type == '%' ? $faker->numberBetween(0, 100) : $faker->randomNumber(5);
    return [
        'name' => $faker->word,
        'type' => $faker->randomElement(['fixed', '%']),
        'amount' => $amount,
        'purchase_order_id' => factory(PurchaseOrder::class)->create()
    ];
});

$factory->define(Item::class, function (Faker\Generator $faker) {
    return [
        'sku' => str_random(10),
        'brand' => $faker->name,
        'name' => $faker->word,
        'specification' => $faker->paragraph(2),
        'company_id' => factory(Company::class)->create()->id
    ];
});

$factory->define(LineItem::class, function (Faker\Generator $faker) {
    $purchaseRequest = factory(PurchaseRequest::class)->create();
    return [
        'quantity' => $purchaseRequest->quantity,
        'price' => $faker->randomNumber(6),
        'payable' => $faker->date('d/m/Y'),
        'delivery' => $faker->date('d/m/Y'),
        'purchase_request_id' => $purchaseRequest->id,
        'purchase_order_id' => factory(PurchaseOrder::class)->create()->id
    ];
});



$factory->define(Photo::class, function (Faker\Generator $faker) {
    $name = $faker->word;
    return [
        'name' => $name . '.jpg',
        'path' => 'uploads/test/' . $name . '.jpg',
        'thumbnail_path' => 'uploads/test/tn_' . $name . '.jpg'
    ];
});


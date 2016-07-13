<?php

use App\Company;
use App\Subscription;
use App\User;
use Illuminate\Database\Seeder;

class LiveAccountSeeder extends Seeder
{

    /**
     * List of table names that we affect
     * @var array
     */
    protected $tables = [
        'companies',
        'users',
        'items',
        'projects',
        'vendors',
        'addresses',
        'roles',
        'bank_accounts',
        'purchase_requests',
        'purchase_orders',
        'line_items',
        'subscriptions'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear our tables - Make our records
        $this->truncateTables()
             ->setUpCompany()
//             ->createSubscription()
             ->createUserMike();
    }

    /**
     * Set up the main Company and related info
     */
    protected function setUpCompany()
    {
        $this->command->info('Company... ');
        $company = Company::create([
            'name' => 'Sabersky',
            'description' => 'Cloud purchasing system for growing companies'
        ]);

        $company->settings()->update([
            'currency_decimal_points' => 2,
            'currency_id' => 840
        ]);

        // Give pusaka a address!
        $company->address()->create([
            'contact_person' => 'Michael Wu',
            'phone' => '+819077569898',
            'address_1' => '17 Mccullock Rise BullCreek',
            'city' => 'Perth',
            'zip' => '6149',
            'state' => 'WA',
            'country_id' => 36
        ]);

        $this->company = $company;

        return $this;
    }

    protected function createSubscription()
    {
        $this->command->info('Subscription... ');

        Subscription::create([
            'company_id' => $this->company->id,
            'name' => 'main',
            'stripe_id' => str_random(21),
            'stripe_plan' => 'growth',
            'quantity' => 1
        ]);

        return $this;
    }

    protected function createUserMike()
    {
        $this->command->info('User - Mike... ');
        $this->user = User::create([
            'name' => 'Mike Wu',
            'email' => 'mike@sabersky.com',
            'password' => bcrypt('password')
        ]);


        $this->company->employees()->save($this->user);

        // Turn our user into an admin
        $role = $this->company->createAdmin();
        $role->giveAdminPermissions();
        $this->user->setRole($role);

        return $this;
    }


    protected function truncateTables()
    {
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        return $this;
    }


}


<?php

use App\Company;
use App\User;
use Illuminate\Database\Seeder;

class LiveAccountSeeder extends Seeder
{
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
             ->createSubscription()
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
        factory(Subscription::class)->create([
            'company_id' => $this->company->id
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


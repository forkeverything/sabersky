<?php

use App\Address;
use App\Company;
use App\Item;
use App\Project;
use App\User;
use App\Vendor;
use Illuminate\Database\Seeder;

class PusakaSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->truncate();
        DB::table('users')->truncate();
        DB::table('items')->truncate();
        DB::table('projects')->truncate();
        DB::table('vendors')->truncate();

        $company = Company::create([
            'name' => 'Pusaka Jaya',
            'description' => 'EPC Contractor & Independent Power Producer for over 20 years.'
        ]);

        $company->settings()->update([
            'currency_id' => 360
        ]);

        // Give pusaka a address!
        $company->address()->create([
            'contact_person' => 'Albert Wu',
            'phone' => '0816827592',
            'address_1' => 'JL 1 & 2 Megakuningan',
            'address_2' => 'Setiabudi',
            'city' => 'DKI Jakarta',
            'zip' => '237952',
            'state' => 'Jawa',
            'country_id' => 360
        ]);

        $user = User::create([
            'name' => 'Michael Sutono',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password')
        ]);

        $company->employees()->save($user);

        $role = $company->createAdmin();
        $role->giveAdminPermissions();

        $user->setRole($role);

        $project = $company->projects()->create([
            'name' => 'Tanjung Selor 7MW',
            'location' => 'Jawa Tengah',
            'description' => 'Gallia est omnis divisa in partes tres, quarum. Ab illo tempore, ab est sed immemorabili. Nihil hic munitissimus habendi senatus locus, nihil horum? Quam diu etiam furor iste tuus nos eludet? Idque Caesaris facere voluntate liceret: sese habere. Magna pars studiorum, prodita quaerimus.
A communi observantia non est recedendum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Quae vero auctorem tractata ab fiducia dicuntur. Quam temere in vitiis, legem sancimus haerentia. Unam incolunt Belgae, aliam Aquitani, tertiam. Curabitur est gravida et libero vitae dictum.'
        ]);

        $user->projects()->save($project);

        // Vendors
        $verifiedVendors = factory(Vendor::class, 3)->create([
            'base_company_id' => 1,
            'verified' => 1
        ]);

        foreach ($verifiedVendors as $vendor) {
            factory(Address::class)->create([
                'owner_id' => $vendor->linkedCompany->id,
                'owner_type' => 'company'
            ]);
        }

        $pendingVendors = factory(Vendor::class, 2)->create([
            'base_company_id' => 1,
            'verified' => 0
        ]);

        foreach ($pendingVendors as $vendor) {
            factory(Address::class)->create([
                'owner_id' => $vendor->id
            ]);
            factory(Address::class)->create([
                'owner_id' => $vendor->linkedCompany->id,
                'owner_type' => 'company'
            ]);
        }

        $customVendor = factory(Vendor::class)->create([
            'base_company_id' => 1,
            'linked_company_id' => null
        ]);

        factory(Address::class, 3)->create([
            'owner_id' => $customVendor->id
        ]);

        // Purchase Requests
            factory(\App\PurchaseRequest::class, 10)->create([
                'state' => 'open',
                'project_id' => 1,
                'item_id' => factory(Item::class)->create([
                    'company_id' => 1
                ])->id,
                'user_id' => factory(\App\User::class)->create([
                    'company_id' => 1
                ])->id
            ]);
    }
}

<?php

use App\Address;
use App\BankAccount;
use App\Company;
use App\Item;
use App\Project;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\User;
use App\Vendor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PusakaSetupSeeder extends Seeder
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
        'line_items'
    ];

    protected $company;

    protected $user;

    protected $project;

    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

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
             ->createUserMike()
             ->makeProject()
             ->createVendors()
             ->createPurchaseOrders();


        $this->company->employees()->save($this->user);

        // Turn our user into an admin
        $role = $this->company->createAdmin();
        $role->giveAdminPermissions();
        $this->user->setRole($role);


        $this->user->projects()->save($this->project);
    }

    protected function truncateTables()
    {
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        return $this;
    }

    /**
     * Set up the main Company and related info
     */
    protected function setUpCompany()
    {
        $company = Company::create([
            'name' => 'Pusaka Jaya',
            'description' => 'EPC Contractor & Independent Power Producer for over 20 years.'
        ]);

        $company->settings()->update([
            'currency_decimal_points' => 0,
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

        $this->company = $company;

        return $this;
    }

    protected function createUserMike()
    {
        $this->user = User::create([
            'name' => 'Michael Sutono',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password')
        ]);

        return $this;
    }

    protected function makeProject()
    {
        $this->project = $this->company->projects()->create([
            'name' => 'Tanjung Selor 7MW',
            'location' => 'Jawa Tengah',
            'description' => 'Gallia est omnis divisa in partes tres, quarum. Ab illo tempore, ab est sed immemorabili. Nihil hic munitissimus habendi senatus locus, nihil horum? Quam diu etiam furor iste tuus nos eludet? Idque Caesaris facere voluntate liceret: sese habere. Magna pars studiorum, prodita quaerimus.
A communi observantia non est recedendum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Quae vero auctorem tractata ab fiducia dicuntur. Quam temere in vitiis, legem sancimus haerentia. Unam incolunt Belgae, aliam Aquitani, tertiam. Curabitur est gravida et libero vitae dictum.'
        ]);
        return $this;
    }

    protected function createVendors()
    {
        // Verified vendors
        $verifiedVendors = factory(Vendor::class, 3)->create([
            'base_company_id' => 1,
            'verified' => 1
        ]);
        foreach ($verifiedVendors as $vendor) {
            factory(Address::class)->create([
                'owner_id' => $vendor->linkedCompany->id,
                'owner_type' => 'company'
            ]);
            factory(BankAccount::class, 3)->create([
                'vendor_id' => $vendor->id
            ]);
        }

        // Pending Vendors
        $pendingVendors = factory(Vendor::class, 2)->create([
            'base_company_id' => 1,
            'verified' => 0,
        ]);
        foreach ($pendingVendors as $vendor) {
            factory(Address::class)->create([
                'owner_id' => $vendor->id
            ]);
            factory(Address::class)->create([
                'owner_id' => $vendor->linkedCompany->id,
                'owner_type' => 'company'
            ]);
            factory(BankAccount::class, 2)->create([
                'vendor_id' => $vendor->id
            ]);
        }

        // Custom Vendor
        $customVendor = factory(Vendor::class)->create([
            'base_company_id' => 1,
            'linked_company_id' => null
        ]);
        factory(Address::class, 3)->create([
            'owner_id' => $customVendor->id
        ]);

        factory(BankAccount::class, 1)->create([
            'vendor_id' => $customVendor->id
        ]);

        // Purchase Requests
        factory(PurchaseRequest::class, 10)->create([
            'state' => 'open',
            'project_id' => 1,
            'item_id' => factory(Item::class)->create([
                'company_id' => 1
            ])->id,
            'user_id' => factory(\App\User::class)->create([
                'company_id' => 1
            ])->id
        ]);

        return $this;
    }

    protected function createPurchaseOrders()
    {
        // how many to make?
        $numberOfOrders = $this->faker->numberBetween(1, 10);
        // For each PO
        for ($i = 0; $i < $numberOfOrders; $i++) {
            // Select a random vendor
            $vendor = $this->company->vendors->random();
            // select random address
            $vendorAddress = $vendor->addresses->count() ? $vendor->addresses->random() : $vendor->linkedCompany->address;
            // select a random bank account
            $vendorBankAccount = $vendor->bank_accounts->random();
            // Billing address random yes / no
            $billingSameAsCompany = $this->faker->boolean(50);
            $billingAddress = $billingSameAsCompany ? $this->company->address : factory(Address::class)->create(['owner_id' => 0, 'owner_type' => '']);
            // Shipping address - same as billing
            $shippingAddressSameAsBilling = $this->faker->boolean(50);
            $shippingAddress = $shippingAddressSameAsBilling ? $billingAddress : factory(Address::class)->create(['owner_id' => 0, 'owner_type' => '']);
            // Make our PO
            $order = PurchaseOrder::create([
                'vendor_id' => $vendor->id,
                'vendor_address_id' => $vendorAddress->id,
                'vendor_bank_account_id' => $vendorBankAccount->id,
                'currency_id' => $this->faker->randomElement(['360', '840', '392']),
                'user_id' => factory(User::class)->create(['company_id' => $this->company->id])->id,
                'company_id' => $this->company->id
            ]);

            // Every order needs to have at least 1 lineItem
            while (PurchaseOrder::find($order->id)->lineItems->count() < 1) {

                $allQuantitiesRemaining = Company::find($this->company->id)->purchaseRequests->pluck('quantity')->toArray();
                // If we don't have any more PR's that need servicing make some
                if (!array_sum($allQuantitiesRemaining)) {
                    factory(PurchaseRequest::class, 1)->create([
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

                $purchaseRequests = Company::find($this->company->id)->purchaseRequests->random($this->faker->numberBetween(1, 10));
                if ($purchaseRequests instanceof PurchaseRequest) {
                    $request = PurchaseRequest::find($purchaseRequests->id);
                    $this->makeLineItem($order, $request);
                } else {
                    foreach ($purchaseRequests as $purchaseRequest) {
                        $request = PurchaseRequest::find($purchaseRequest->id);
                        $this->makeLineItem($order, $request);
                    }
                }
            }

            for ($x = 0; $x < $this->faker->numberBetween(0, 3); $x++) {
                $order->additionalCosts()->create([
                    'name' => $this->faker->randomElement(['discount', 'shipping', 'tax', 'gift card']),
                    'type' => $this->faker->randomElement(['%', 'fixed']),
                    'amount' => $this->faker->randomNumber(2)
                ]);
            }

            $order->attachBillingAndShippingAddresses($billingAddress, $shippingAddress)
                  ->updatePurchaseRequests()
                  ->attachRules()
                  ->tryAutoApprove();
        }
    }

    protected function makeLineItem($order, $request)
    {
        if (!$request->quantity) return;
        $lineItem = \App\LineItem::create([
            'quantity' => $this->faker->numberBetween(1, $request->quantity),
            'purchase_request_id' => $request->id,
            'purchase_order_id' => $order->id,
            'price' => $this->faker->randomNumber(3),
            'payable' => $this->faker->dateTimeBetween('now', '+1 year')->format('d/m/Y'),
            'delivery' => $this->faker->dateTimeBetween('now', '+1 year')->format('d/m/Y'),
        ]);
    }
}

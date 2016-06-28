<?php

use App\Address;
use App\BankAccount;
use App\Company;
use App\Factories\PurchaseOrderFactory;
use App\Http\Requests\AddItemRequest;
use App\Http\Requests\AddNewVendorRequest;
use App\Http\Requests\StartProjectRequest;
use App\Item;
use App\LineItem;
use App\ProductSubcategory;
use App\Project;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\Role;
use App\Rule;
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
             ->createPurchaseRequests()
             ->makeRules()
             ->createPurchaseOrders()
        ;
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
        $this->command->info('Company... ');
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
        $this->command->info('User - Mike... ');
        $this->user = User::create([
            'name' => 'Michael Sutono',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password')
        ]);


        $this->company->employees()->save($this->user);

        // Turn our user into an admin
        $role = $this->company->createAdmin();
        $role->giveAdminPermissions();
        $this->user->setRole($role);

        return $this;
    }

    protected function makeProject()
    {
        $this->command->info('Project... ');
        $request = new StartProjectRequest([
            'name' => 'Tanjung Selor 7MW',
            'location' => 'Jawa Tengah',
            'description' => 'Gallia est omnis divisa in partes tres, quarum. Ab illo tempore, ab est sed immemorabili. Nihil hic munitissimus habendi senatus locus, nihil horum? Quam diu etiam furor iste tuus nos eludet? Idque Caesaris facere voluntate liceret: sese habere. Magna pars studiorum, prodita quaerimus.
A communi observantia non est recedendum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Quae vero auctorem tractata ab fiducia dicuntur. Quam temere in vitiis, legem sancimus haerentia. Unam incolunt Belgae, aliam Aquitani, tertiam. Curabitur est gravida et libero vitae dictum.'
        ]);

        Project::start($request, $this->user);

        return $this;
    }

    protected function createVendors()
    {
        $this->command->info('Vendors... ');

        // Custom Vendors
        for($i = 0; $i < 5; $i ++) {
            $request = new AddNewVendorRequest([
                'name' => 'PT.' . $this->faker->company,
                'description' => $this->faker->paragraph(3),
                'company_id' => $this->company->id
            ]);
            $vendor = Vendor::add($request, $this->user);

            factory(Address::class, 3)->create([
                'owner_id' => $vendor->id,
                'owner_type' => 'App\Vendor'
            ]);

            factory(BankAccount::class)->create([
                'vendor_id' => $vendor->id
            ]);
        }

        return $this;
    }

    protected function createPurchaseRequests()
    {
        $this->command->info('Purchase Requests... ');
        // Purchase Requests
        for ($i = 0; $i < 10; $i++) {
            $request = new AddItemRequest([
                'sku' => str_random(10),
                'brand' => $this->faker->name,
                'name' => $this->faker->word,
                'specification' => $this->faker->paragraph(2),
                'company_id' => $this->company->id,
                'product_subcategory_id' => $this->faker->randomElement(ProductSubcategory::all()->pluck('id')->toArray())
            ]);
            $user = factory(User::class)->create([
                'company_id' => $this->company->id,
                'role_id' => factory(Role::class)->create([
                    'company_id' => $this->company->id
                ])->id
            ]);
            $item = Item::add($request, $user);
            factory(PurchaseRequest::class)->create([
                'state' => 'open',
                'project_id' => 1,
                'item_id' => $item->id,
                'user_id' => $user->id
            ]);
        }

        return $this;
    }


    protected function createPurchaseOrders()
    {
        $this->command->info('Purchase Orders... ');
        // how many to make?
        $numberOfOrders = $this->faker->numberBetween(1, 50);
        // For each PO
        for ($i = 0; $i < $numberOfOrders; $i++) {
            // Select a random vendor
            $vendor = $this->company->vendors->random();
            // select random address
            $vendorAddress = $vendor->addresses->random();
            // select a random bank account
            $vendorBankAccount = $vendor->bank_accounts->random();
            // Billing address random yes / no
            $billingSameAsCompany = $this->faker->boolean(50);
            $billingAddress = $billingSameAsCompany ? $this->company->address : factory(Address::class)->create(['owner_id' => 0, 'owner_type' => '']);
            // Shipping address - same as billing
            $shippingAddressSameAsBilling = $this->faker->boolean(50);
            $shippingAddress = $shippingAddressSameAsBilling ? $billingAddress : factory(Address::class)->create(['owner_id' => 0, 'owner_type' => '']);
            // Make our PO
            $userMakingOrder = factory(User::class)->create([
                'company_id' => $this->company->id,
                'role_id' => factory(Role::class)->create([
                    'company_id' => $this->company->id
                ])->id
            ]);
            $order = PurchaseOrder::create([
                'vendor_id' => $vendor->id,
                'vendor_address_id' => $vendorAddress->id,
                'vendor_bank_account_id' => $vendorBankAccount->id,
                'currency_id' => $this->faker->randomElement(['360', '840', '392']),
                'user_id' => $userMakingOrder->id,
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
                            'company_id' => $this->company->id
                        ])->id,
                        'user_id' => factory(User::class)->create([
                            'company_id' => $this->company->id,
                            'role_id' => factory(Role::class)->create([
                                'company_id' => $this->company->id
                            ])->id
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

            PurchaseOrderFactory::change($order, $userMakingOrder)->processNewPurchaseOrder($billingAddress, $shippingAddress);
        }
    }

    protected function makeLineItem($order, $request)
    {
        if (!$request->quantity) return;

        $previousOrderedLineItemsForSameItem = $request->item->lineItems;
        $price = null;

        foreach ($previousOrderedLineItemsForSameItem as $lineItem) {
            if ($lineItem->purchase_order_id === $order->id) {
                $price = $lineItem->price;
                break;
            }
        }

        $lineItem = LineItem::add([
            'quantity' => $this->faker->numberBetween(1, $request->quantity),
            'purchase_request_id' => $request->id,
            'purchase_order_id' => $order->id,
            'price' => $price ?: $this->faker->randomNumber(3),
            'payable' => $this->faker->dateTimeBetween('now', '+1 year')->format('d/m/Y'),
            'delivery' => $this->faker->dateTimeBetween('now', '+1 year')->format('d/m/Y'),
        ], User::find($order->user_id));
    }

    protected function makeRules()
    {
        $this->command->info('Rules... ');
        $orderTotalExceeds = Rule::create([
            'rule_property_id' => 1,
            'rule_trigger_id' => 1,
            'limit' => 1000,
            'currency_id' => 840,
            'company_id' => $this->company->id
        ]);


        $orderTotalExceeds->attachUserRole($this->user);

        $newVendor = Rule::create([
            'rule_property_id' => 2,
            'rule_trigger_id' => 2,
            'company_id' => $this->company->id
        ]);

        $newVendor->attachUserRole($this->user);

        return $this;
    }
}

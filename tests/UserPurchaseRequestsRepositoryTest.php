<?php

use App\Company;
use App\Item;
use App\ProductCategory;
use App\Project;
use App\PurchaseRequest;
use App\Repositories\UserPurchaseRequestsRepository;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserPurchaseRequestsRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected static $user;

    protected static $company;

    /**
     * Query properties we attach to the retrieved set to indicate
     * back to the Client the performed search property: filter,
     * urgent, sortableFields, sort, order, paginated.
     *
     * @var int
     */
    protected static $numQueryProperties = 6;

    /**
     * Run this before each Test. We
     * will probably need to make
     * a Company and User
     */
    public function setUp()
    {
        parent::setUp();
        static::$company = factory(Company::class)->create();
        static::$user = factory(User::class)->create(['company_id' => static::$company->id]);
    }

    /** @test */
    public function it_creates_a_new_instance()
    {
        $instance = UserPurchaseRequestsRepository::forUser(static::$user);
        $this->assertTrue($instance instanceof UserPurchaseRequestsRepository);
    }

    /**
     * Just a little helper function to
     * help generate a Project for us
     * to test with
     *
     * @return mixed
     */
    protected function makeProject(User $user = null)
    {
        $project = factory(Project::class)->create([
            'company_id' => static::$company->id
        ]);
        if ($user) {
            $project->addTeamMember($user);
        }
        return $project;
    }

    /**
     * Makes generating PRs easier
     * @param int $num
     * @param null $project
     * @return mixed
     */
    protected function makePurchaseRequests($num = 1, $project = null, $attributes = [], $item = null)
    {
        $project = $project ?: $this->makeProject();
        $testSpecificAttributes = array_merge($attributes, [
            'item_id' => $item ? $item->id : factory(Item::class)->create([
                'company_id' => static::$company->id
            ])->id,
            'user_id' => static::$user->id,
            'project_id' => $project->id
        ]);
        return factory(PurchaseRequest::class, $num)->create($testSpecificAttributes)->load(['project', 'user', 'item']);
    }

    /** @test */
    public function it_finds_relevant_PRs_for_user()
    {

        // Start with 0
        $this->assertNull(UserPurchaseRequestsRepository::forUser(static::$user)->get()->first());

        // Project that user is a part of
        $project1 = $this->makeProject();
        $project1->addTeamMember(static::$user);
        $this->makePurchaseRequests(5, $project1);

        // Project user is NOT a part of
        $project2 = $this->makeProject();
        $this->makePurchaseRequests(10, $project2);

        // Got something ...
        $this->assertNotNull(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->get()->first());
        // Got 5 as expected? (6 properties + 5 actual requests = 11)
        $this->assertCount(5, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->getWithoutQueryProperties());
    }

    /**
     * @test
     */

    public function it_filters_correct_PR_state()
    {

        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        // Make 5 open PRs
        $openPRs = $this->makePurchaseRequests(5, $project, ['state' => 'open']);
        // Make 10 Cancelled PRs
        $cancelledPRs = $this->makePurchaseRequests(10, $project, ['state' => 'cancelled']);
        // Complete 3 PRs
        $completePRs = $this->makePurchaseRequests(3, $project, ['quantity' => 0]);


        // Control - No specified State method (w/o default of 'open'), should get all
        $this->assertCount(18, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->getWithoutQueryProperties());

        // Give no value ie. $state = null (should default to open states)
        $this->assertEquals(5, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState(null)->getWithoutQueryProperties()->count());

        // Given State - does it identify correctly?
        $this->assertCount(5, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('open')->getWithoutQueryProperties());
        $this->assertCount(10, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('cancelled')->getWithoutQueryProperties());
        $this->assertCount(3, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('fulfilled')->getWithoutQueryProperties());
        $this->assertCount(18, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('all')->getWithoutQueryProperties());

        // Give wrong value - default to 'open' States
        $this->assertCount(5, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('foobar')->getWithoutQueryProperties());
    }

    /** @test */
    public function it_sorts_purchase_requests_correctly()
    {
        // Make 20 Different Items
        $items = factory(Item::class, 10)->create([
            'company_id' => static::$company->id
        ]);


        // Single Project
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);


        // Create a PR for each item
        foreach ($items as $item) {
            $this->makePurchaseRequests(1, $project, ['item_id' => $item->id]);
        }

        $generatedPRs = PurchaseRequest::where('project_id', $project->id)->with(['project', 'user', 'item'])->get();


        $sortPRNumberAsc = $generatedPRs->sortBy('number');
        $sortPRNumberDesc = $generatedPRs->sortByDesc('number');


        // No sort No Order - default to  number
        $this->assertEquals($sortPRNumberAsc->pluck('number'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn()->getWithoutQueryProperties()->pluck('number'));

        // With Order
        // asc
        $this->assertEquals($sortPRNumberAsc->pluck('number'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'asc')->getWithoutQueryProperties()->pluck('number'));
        // desc
        $this->assertEquals($sortPRNumberDesc->pluck('number'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'desc')->getWithoutQueryProperties()->pluck('number'));
        // Invalid string
        $this->assertEquals($sortPRNumberAsc->pluck('number'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'awdwadawdwadawdawd')->getWithoutQueryProperties()->pluck('number'));

        // With sort
        // due
        $this->assertEquals($generatedPRs->sortBy('due')->pluck('due'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('due', 'asc')->getWithoutQueryProperties()->pluck('due'));
        // quantity
        $this->assertEquals($generatedPRs->sortBy('quantity')->pluck('quantity'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('quantity', 'asc')->getWithoutQueryProperties()->pluck('quantity'));
        // item name
        $this->assertEquals($generatedPRs->sortBy('item.name')->pluck('item.name'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('item_name', 'asc')->getWithoutQueryProperties()->pluck('item_name'));
        // project name
        $this->assertEquals($generatedPRs->sortBy('project.name')->pluck('project.name'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('project_name', 'asc')->getWithoutQueryProperties()->pluck('project_name'));
        // user name
        $this->assertEquals($generatedPRs->sortBy('user.name')->pluck('user.name'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('requester_name', 'asc')->getWithoutQueryProperties()->pluck('requester_name'));


        // Wrong sort - default to name
        $this->assertEquals($sortPRNumberAsc->pluck('number'), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('foobar', 'bazooka')->getWithoutQueryProperties()->pluck('number'));
    }

    /** @test */
    public function it_filters_on_urgent()
    {
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        $generatedPRs = $this->makePurchaseRequests(20, $project);

        $urgentPRs = $generatedPRs->filter(function ($pr) {
            return $pr->urgent;
        });

        // Ugent Flag
        // Yes
        $this->assertCount(count($urgentPRs), UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent(1)->getWithoutQueryProperties());
        // Not
        $this->assertCount(20, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent(0)->getWithoutQueryProperties());
        // Invalid - fetches all
        $this->assertCount(20, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent('oiauwdbouawbdouawbed')->getWithoutQueryProperties());
    }

    /** @test */
    public function it_filters_on_project()
    {
        // Make 5 for 1 project
        $project_1 = $this->makeProject();
        $project_1->addTeamMember(static::$user);
        $this->makePurchaseRequests(5, $project_1);

        // Make 10 for another project
        $project_2 = $this->makeProject();
        $project_2->addTeamMember(static::$user);
        $this->makePurchaseRequests(10, $project_2);

        // We should see all 15
        $this->assertCount(15, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->getWithoutQueryProperties());

        // Apply filter for project
        // We should see 5 only
        $this->assertCount(5, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->forProject($project_1->id)->getWithoutQueryProperties());
    }

    /** @test */
    public function it_filters_on_integer_field()
    {
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        // Make 3
        $this->makePurchaseRequests(1, $project, ['quantity' => 5]);
        $this->makePurchaseRequests(1, $project, ['quantity' => 8]);
        $this->makePurchaseRequests(1, $project, ['quantity' => 12]);

        // get all 3
        $this->assertCount(3, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->getWithoutQueryProperties());

        // Conditions (array): ["min max", expected]
        $conditions = [
            [" 4", 0],
            [" 5", 1],
            ["5 7", 1],
            ["5 8", 2],
            ["5 10", 2],
            ["5 12", 3],
            ["10 ", 1],
            ["13 ", 0]
        ];

        foreach ($conditions as $condition) {
            $this->assertCount($condition[1], UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->filterIntegerField('quantity', $condition[0])->getWithoutQueryProperties());
        }
    }

    /**
     * @test
     */
    public function it_filters_on_item_brand_and_or_name()
    {
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        // 4 Items: Control (no match), Brand match, Name match, Both Match
        $itemsAttributes = [
            // Control
            [
                'company_id' => static::$company->id
            ],
            // Brand
            [
                'brand' => 'nike',
                'company_id' => static::$company->id
            ],
            // Name
            [
                'name' => 'Air Ball',
                'company_id' => static::$company->id
            ],
            // Both
            [
                'brand' => 'nike',
                'name' => 'Air Ball',
                'company_id' => static::$company->id
            ]
        ];

        // Create Items
        foreach ($itemsAttributes as $itemsAttribute) {
            $item = factory(Item::class)->create($itemsAttribute);
            $this->makePurchaseRequests(1, $project, [], $item);
        }

        // Conditions array = ['brand', 'name', expected count (int)]
        $conditions = [
            // No Filter - Get all 4 items
            [null, null, 4],
            // Match Brand - find 2 items
            ['nike', null, 2],
            // Match Name - find 2 items
             [null, 'air ball', 2],
            // Match Both - find 1 item
            ['nike', 'AIR BALL', 1],
            // No match - find 0 item
            ['foo', 'bar', 0]
        ];

        // Actually make assertions
        foreach ($conditions as $condition) {
            $this->assertCount($condition[2], UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->filterByItem($condition[0], $condition[1])->getWithoutQueryProperties());
        }
    }

    /**
     * @test
     */
    public function it_filters_on_a_date_field()
    {
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        // Make 3
        $dates = [
            // A
            '2016-06-01 10:32:09',
            // B
            '2016-09-12 10:32:09',
            // C
            '2016-12-31 10:32:09'
        ];

        // Make 3 requests for each date
        foreach ($dates as $date) {
            $pr = $this->makePurchaseRequests(1, $project);
            $pr->created_at = $date;
            $pr->save();
        }

        // Conditions (array): ["min max", expected]
        $dateConditions = [
            // Unset
            ["  ", 3],
            // Before A
            [" 2016-05-30", 0],
//            // Before and including A
            [" 2016-06-02", 1],
//            // After and including B
            ["2016-09-12 ", 2],
//            // From A to C
            ["2016-06-01, 2017-01-01", 3],
//            // From After C
            ["2017-01-01 ", 0],
        ];

        foreach ($dateConditions as $condition) {
            if($condition[0] == " 2016-06-01") dd(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->filterDateField('purchase_requests.created_at', $condition[0])->getWithoutQueryProperties()->pluck('created_at'));
            $this->assertCount($condition[1], UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->filterDateField('purchase_requests.created_at', $condition[0])->getWithoutQueryProperties());
        }
    }

    /**
     * @test
     */
    public function it_finds_the_right_requests_for_a_category()
    {
        $targetCategory = ProductCategory::all()->random();

        // Make 5 items for target cat
        for ($i = 0; $i < 5; $i++) {
            $item = factory(Item::class)->create([
                'company_id' => static::$company->id,
                'product_subcategory_id' => $targetCategory->subcategories->random()->id
            ]);
            $project = $this->makeProject(static::$user);
            $this->makePurchaseRequests(1, $project, ['state' => 'open'], $item);
        }

        // Make 10 for different categories
        for ($j = 0; $j < 10; $j++) {
            do {
                $irrelevantCategory = ProductCategory::all()->random();
            } while ($irrelevantCategory->id === $targetCategory->id);
            $item = factory(Item::class)->create([
                'company_id' => static::$company->id,
                'product_subcategory_id' => $irrelevantCategory->subcategories->random()->id
            ]);
            $project = $this->makeProject(static::$user);
            $this->makePurchaseRequests(1, $project, ['state' => 'open'], $item);
        }


        // Unfiltered get 15
        $this->assertCount(15, UserPurchaseRequestsRepository::forUser(static::$user)->getWithoutQueryProperties());

        // Target Category = 5
        $this->assertCount(5, UserPurchaseRequestsRepository::forUser(static::$user)->belongsToProductCategory($targetCategory->id)->getWithoutQueryProperties());


    }




}

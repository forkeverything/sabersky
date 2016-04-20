<?php

use App\Company;
use App\Item;
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
    protected function makeProject()
    {
        return factory(Project::class)->create([
            'company_id' => static::$company->id
        ]);
    }

    /**
     * Makes generating PRs easier
     * @param int $num
     * @param null $project
     * @return mixed
     */
    protected function makePurchaseRequests($num = 1, $project = null, $attributes = [])
    {
        $project = $project ?: $this->makeProject();
        $testSpecificAttributes = array_merge($attributes, [
            'item_id' => factory(Item::class)->create([
                'company_id' => static::$company->id
            ])->id,
            'user_id' => static::$user->id,
            'project_id' => $project->id
        ]);
        return factory(PurchaseRequest::class, $num)->create($testSpecificAttributes)->load(['project', 'user', 'item']);
    }

    /** @test */
    public function it_finds_the_right_purchase_requests()
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
        $this->assertCount(5 + static::$numQueryProperties, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->get());
    }

    /** @test */
    public function it_finds_correct_PR_state()
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
        $this->assertCount(18 + static::$numQueryProperties, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->get());

        // Give no value ie. $state = null (should default to open states)
        $this->assertEquals(5 + static::$numQueryProperties, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState()->get()->count());

        // Given State - does it identify correctly?
        $this->assertEquals(count($openPRs) + static::$numQueryProperties, UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('open')->get()->count());
        $this->assertEquals(count($cancelledPRs) + static::$numQueryProperties, count(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('cancelled')->get()));
        $this->assertEquals(count($completePRs) + static::$numQueryProperties, count(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('complete')->get()));
        $this->assertEquals((count($openPRs) + count($cancelledPRs) + count($completePRs)) + static::$numQueryProperties, count(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('all')->get()));

        // Give wrong value - default to 'open' States
        $this->assertEquals(11, count(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->whereState('foobar')->get()));
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


        $sortItemNameAsc = $generatedPRs->sortBy('item.name');
        $sortItemNameDesc = $generatedPRs->sortByDesc('item.name');


        // No sort No Order - default to name + asc
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn()->get()->pluck('item_name')->toArray()));

        // With Order
        // asc
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'asc')->get()->pluck('item_name')->toArray()));
        // desc
        $this->assertEquals($sortItemNameDesc->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'desc')->get()->pluck('item_name')->toArray()));
        // Invalid string
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn(null, 'awdwadawdwadawdawd')->get()->pluck('item_name')->toArray()));

        // With sort
        // due
        $this->assertEquals($generatedPRs->sortBy('due')->pluck('due')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('due', 'asc')->get()->pluck('due')->toArray()));
        // quantity
        $this->assertEquals($generatedPRs->sortBy('quantity')->pluck('quantity')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('quantity', 'asc')->get()->pluck('quantity')->toArray()));
        // item name
        $this->assertEquals($generatedPRs->sortBy('item.name')->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('item_name', 'asc')->get()->pluck('item_name')->toArray()));
        // project name
        $this->assertEquals($generatedPRs->sortBy('project.name')->pluck('project.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('project_name', 'asc')->get()->pluck('project_name')->toArray()));
        // user name
        $this->assertEquals($generatedPRs->sortBy('user.name')->pluck('user.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('requester_name', 'asc')->get()->pluck('requester_name')->toArray()));


        // Wrong sort - default to name
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->sortOn('foobar', 'bazooka')->get()->pluck('item_name')->toArray()));
    }

    /** @test */
    public function it_only_retrieves_urgent_requests()
    {
        $project = $this->makeProject();
        $project->addTeamMember(static::$user);

        $generatedPRs = $this->makePurchaseRequests(20, $project);

        $urgentPRs = $generatedPRs->filter(function ($pr) {
            return $pr->urgent;
        });

        // Ugent Flag
        // Yes
        $this->assertEquals($urgentPRs->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent(1)->get()->pluck('item_name')->toArray()));
        // Not
        $this->assertEquals($generatedPRs->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent(0)->get()->pluck('item_name')->toArray()));
        // Invalid
        $this->assertEquals($generatedPRs->pluck('item.name')->toArray(), array_filter(UserPurchaseRequestsRepository::forUser(User::find(static::$user->id))->onlyUrgent('oiauwdbouawbdouawbed')->get()->pluck('item_name')->toArray()));
    }


}

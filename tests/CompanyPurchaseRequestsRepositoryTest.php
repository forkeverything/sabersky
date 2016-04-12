<?php

use App\Company;
use App\Item;
use App\Project;
use App\PurchaseRequest;
use App\Repositories\CompanyPurchaseRequestsRepository;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyPurchaseRequestsRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected static $user;

    protected static $company;

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
        $instance = CompanyPurchaseRequestsRepository::forCompany(static::$company);
        $this->assertTrue($instance instanceof CompanyPurchaseRequestsRepository);
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

        $project1 = $this->makeProject();

        $project2 = $this->makeProject();

        // Start with 0
        $this->assertNull(CompanyPurchaseRequestsRepository::forCompany(static::$company)->get()->first());

        // Make 5 for 1 Project
        $this->makePurchaseRequests(5, $project1);
        // 10 for another Project
        $this->makePurchaseRequests(10, $project2);

        // Got something ...
        $this->assertNotNull(CompanyPurchaseRequestsRepository::forCompany(static::$company)->get()->first());
        // Got 15 as expected?
        $this->assertCount(21, CompanyPurchaseRequestsRepository::forCompany(static::$company)->get());
    }

    /** @test */
    public function it_applies_correct_filters()
    {

        // Make 5 open PRs
        $openPRs = $this->makePurchaseRequests(5, null, ['state' => 'open']);
        // Make 10 Cancelled PRs
        $cancelledPRs = $this->makePurchaseRequests(10, null, ['state' => 'cancelled']);
        // Complete 3 PRs
         $completePRs = $this->makePurchaseRequests(3, null, ['quantity' => 0]);



        // Control - No Applying filter method (no default of 'open'), should get all 20
        $this->assertCount(24, CompanyPurchaseRequestsRepository::forCompany(static::$company)->get());

        // Give no value ie. $filter = null (should retrieve open states)

        $this->assertEquals(11, CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy()->get()->count());

        // Given filter - does it apply?
        $this->assertEquals(11, CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy('open')->get()->count());
        $this->assertEquals(16, count(CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy('cancelled')->get()));
        $this->assertEquals(9 , count(CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy('complete')->get()));
        $this->assertEquals(24, count(CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy('all')->get()));

        // Give wrong value - default to 'open
        $this->assertEquals(11, count(CompanyPurchaseRequestsRepository::forCompany(static::$company)->filterBy('foobar')->get()));
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


        // Create a PR for each item
        foreach ($items as $item) {
            $this->makePurchaseRequests(1, $project, ['item_id' => $item->id]);
        }

        $generatedPRs = PurchaseRequest::where('project_id', $project->id)->with(['project', 'user', 'item'])->get();


        $sortItemNameAsc = $generatedPRs->sortBy('item.name');
        $sortItemNameDesc = $generatedPRs->sortByDesc('item.name');


        // No sort No Order - default to name + asc
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn()->get()->pluck('item_name')->toArray()));

        // With Order
        // asc
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn(null, 'asc')->get()->pluck('item_name')->toArray()));
        // desc
        $this->assertEquals($sortItemNameDesc->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn(null, 'desc')->get()->pluck('item_name')->toArray()));
        // Invalid string
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn(null, 'awdwadawdwadawdawd')->get()->pluck('item_name')->toArray()));

        // With sort
        // due
        $this->assertEquals($generatedPRs->sortBy('due')->pluck('due')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('due', 'asc')->get()->pluck('due')->toArray()));
        // quantity
        $this->assertEquals($generatedPRs->sortBy('quantity')->pluck('quantity')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('quantity', 'asc')->get()->pluck('quantity')->toArray()));
        // item name
        $this->assertEquals($generatedPRs->sortBy('item.name')->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('item_name', 'asc')->get()->pluck('item_name')->toArray()));
        // project name
        $this->assertEquals($generatedPRs->sortBy('project.name')->pluck('project.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('project_name', 'asc')->get()->pluck('project_name')->toArray()));
        // user name
        $this->assertEquals($generatedPRs->sortBy('user.name')->pluck('user.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('requester_name', 'asc')->get()->pluck('requester_name')->toArray()));


        // Wrong sort - default to name
        $this->assertEquals($sortItemNameAsc->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->sortOn('foobar', 'bazooka')->get()->pluck('item_name')->toArray()));
    }

    /** @test */
    public function it_only_retrieves_urgent_requests()
    {
        $generatedPRs = $this->makePurchaseRequests(20, $this->makeProject());

        $urgentPRs = $generatedPRs->filter(function ($pr) {
            return $pr->urgent;
        });

        // Ugent Flag
        // Yes
        $this->assertEquals($urgentPRs->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->onlyUrgent(1)->get()->pluck('item_name')->toArray()));
        // Not
        $this->assertEquals($generatedPRs->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->onlyUrgent(0)->get()->pluck('item_name')->toArray()));
        // Invalid
        $this->assertEquals($generatedPRs->pluck('item.name')->toArray(), array_filter(CompanyPurchaseRequestsRepository::forCompany(static::$company)->onlyUrgent('oiauwdbouawbdouawbed')->get()->pluck('item_name')->toArray()));
    }


}

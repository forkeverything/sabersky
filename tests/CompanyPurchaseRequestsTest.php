<?php

use App\Company;
use App\Project;
use App\User;
use App\Utilities\CompanyPurchaseRequests;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyPurchaseRequestsTest extends TestCase
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
        $instance = CompanyPurchaseRequests::forCompany(static::$company);
        $this->assertTrue($instance instanceof CompanyPurchaseRequests);
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
    protected function makePurchaseRequests($num = 1, $project = null)
    {
        $project = $project ?: $this->makeProject();
        return factory(App\PurchaseRequest::class, $num)->create([
            'user_id' => static::$user->id,
            'project_id' => $project->id
        ])->load(['project', 'user', 'item']);
    }

    /** @test */
    public function it_finds_the_right_purchase_requests()
    {

        $project1 = $this->makeProject();

        $project2 = $this->makeProject();

        // Start with 0
        $this->assertEmpty(CompanyPurchaseRequests::forCompany(static::$company)->get());

        // Make 5 for 1 Project
        $this->makePurchaseRequests(5, $project1);
        // 10 for another Project
        $this->makePurchaseRequests(10, $project2);

        // Got something ...
        $this->assertNotEmpty(CompanyPurchaseRequests::forCompany(static::$company)->get());
        // Got 15 as expected?
        $this->assertCount(15, CompanyPurchaseRequests::forCompany(static::$company)->get());
    }

    /** @test */
    public function it_applies_correct_filters()
    {
        // Make 20 PRs of various statuses
        $generatedPRs = $this->makePurchaseRequests(20, $this->makeProject());

        $openPRs = $generatedPRs->filter(function($pr){
            return ($pr->state) === 'open';
        });
        $numOpen = count($openPRs);
        $cancelledPRs = $generatedPRs->filter(function($pr){
            return ($pr->state) === 'cancelled';
        });
        $numCancelled = count($cancelledPRs);
        $completedPRs = $generatedPRs->filter(function($pr){
            return $pr->quantity == 0;
        });
        $numComplete = count($completedPRs);





        // Control - No Applying filter method (no default of 'open'), should get all 20
        $this->assertCount(20, CompanyPurchaseRequests::forCompany(static::$company)->get());

        // Give no value ie. $filter = null (should retrieve open states)
        $this->assertEquals($numOpen, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy()->get()));

        // Given filter - does it apply?
        $this->assertEquals($numOpen, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy('open')->get()));
        $this->assertEquals($numCancelled, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy('cancelled')->get()));
        $this->assertEquals($numComplete, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy('complete')->get()));
        $this->assertEquals(20, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy('all')->get()));

        // Give wrong value - default to 'open
        $this->assertEquals($numOpen, count(CompanyPurchaseRequests::forCompany(static::$company)->filterBy('foobar')->get()));
    }

    /** @test */
    public function it_sorts_purchase_requests_correctly()
    {
        // Make 20 PRs
        $generatedPRs = $this->makePurchaseRequests(20, $this->makeProject());


        $sortItemNameAsc = $generatedPRs->sortBy('item.name');
        $sortItemNameDesc = $generatedPRs->sortByDesc('item.name');


        // No sort No Order - default to name + asc
        $this->assertEquals($sortItemNameAsc->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->sortOn()->get()->pluck('item_name'));

        // With Order
            // asc
            $this->assertEquals($sortItemNameAsc->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->sortOn(null, 'asc')->get()->pluck('item_name'));
            // desc
            $this->assertEquals($sortItemNameDesc->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->sortOn(null, 'desc')->get()->pluck('item_name'));
            // Invalid string
            $this->assertEquals($sortItemNameAsc->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->sortOn(null, 'awdwadawdwadawdawd')->get()->pluck('item_name'));

        // With sort
            // due
            $this->assertEquals($generatedPRs->sortBy('due')->pluck('due'),  CompanyPurchaseRequests::forCompany(static::$company)->sortOn('due', 'asc')->get()->pluck('due'));
            // quantity
            $this->assertEquals($generatedPRs->sortBy('quantity')->pluck('quantity'),  CompanyPurchaseRequests::forCompany(static::$company)->sortOn('quantity', 'asc')->get()->pluck('quantity'));
            // item name
            $this->assertEquals($generatedPRs->sortBy('item.name')->pluck('item.name'),  CompanyPurchaseRequests::forCompany(static::$company)->sortOn('item_name', 'asc')->get()->pluck('item_name'));
            // project name
            $this->assertEquals($generatedPRs->sortBy('project.name')->pluck('project.name'),  CompanyPurchaseRequests::forCompany(static::$company)->sortOn('project_name', 'asc')->get()->pluck('project_name'));
            // user name
            $this->assertEquals($generatedPRs->sortBy('user.name')->pluck('user.name'),  CompanyPurchaseRequests::forCompany(static::$company)->sortOn('requester_name', 'asc')->get()->pluck('requester_name'));


        // Wrong sort - default to name
        $this->assertEquals($sortItemNameAsc->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->sortOn('foobar', 'bazooka')->get()->pluck('item_name'));
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
            $this->assertEquals($urgentPRs->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->onlyUrgent(1)->get()->pluck('item_name'));
            // Not
            $this->assertEquals($generatedPRs->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->onlyUrgent(0)->get()->pluck('item_name'));
            // Invalid
            $this->assertEquals($generatedPRs->pluck('item.name'), CompanyPurchaseRequests::forCompany(static::$company)->onlyUrgent('oiauwdbouawbdouawbed')->get()->pluck('item_name'));
    }
    

}

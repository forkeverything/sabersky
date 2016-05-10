<?php

use App\Company;
use App\PurchaseOrder;
use App\Repositories\CompanyPurchaseOrdersRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyPurchaseOrdersRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    public function it_filters_order_by_status()
    {
        // Make purchase orders: 3 pending, 2 approved, 1 rejected (6 all)
        $someCompany = factory(Company::class)->create();
        $pendingOrders = factory(PurchaseOrder::class, 3)->create([
            'status' => 'pending',
            'company_id' => $someCompany->id
        ]);

        foreach ($pendingOrders as $pendingOrder) {
                factory(\App\LineItem::class)->create([
                    'purchase_order_id' => $pendingOrder->id
                ]);
            }

        $approvedOrders = factory(PurchaseOrder::class, 2)->create([
            'status' => 'approved',
            'company_id' => $someCompany->id
        ]);

        foreach ($approvedOrders as $approvedOrder) {
            factory(\App\LineItem::class)->create([
                'purchase_order_id' => $approvedOrder->id
            ]);
        }


        $rejectedOrder = factory(PurchaseOrder::class, 1)->create([
            'status' => 'rejected',
            'company_id' => $someCompany->id
        ]);

            factory(\App\LineItem::class)->create([
                'purchase_order_id' => $rejectedOrder->id
            ]);


        // Got 6!
        $this->assertCount(6, Company::find($someCompany->id)->purchaseOrders);

        $statusAndExpected = [
            'pending' => 3,
            'approved' => 2,
            'rejected' => 1,
            'all' => 6
        ];


        foreach ($statusAndExpected as $status => $expectedCount) {
            $this->assertCount($expectedCount, CompanyPurchaseOrdersRepository::forCompany($someCompany)
                                                                              ->whereStatus($status)
                                                                              ->getWithoutQueryProperties());
        }
    }

    /**
     * @test
     */
    public function it_finds_orders_that_service_a_given_project()
    {
        $someCompany = factory(Company::class)->create();
        /*
         * Test Outline
         * - 3 projects: A, B, C
         * - We want to find Purchase Orders that have requests for a any specific project
         * - Purchase Orders: (projects serviced) = num. of orders
         *      - (A) = 1
         *      - (A & B) = 1
         *      - (A & C) = 1
         *      - (B) = 2
         *      - (B & C) = 1
         *      - (A & B & C) = 1
         */

        // Our projects
        $projects = [
            'A', 'B', 'C'
        ];

        // Create our requests for each project
        foreach ($projects as $project) {
            $proj_NAME = 'proj_' . $project;
            $PR_NAME = 'PR_' . $project;
            $$proj_NAME =  factory(\App\Project::class)->create();
            $$PR_NAME = factory(\App\PurchaseRequest::class)->create([
                'project_id' => $$proj_NAME->id
            ]);
        }

        // Our orders (the projects they service, joined with a '_')
        $orders = [
            'A', 'A_B', 'A_C', 'B', 'B', 'B_C', 'A_B_C'
        ];

        foreach ($orders as $order) {
            $orderName = 'PO_' . $order;
            $$orderName = factory(PurchaseOrder::class)->create([
                'company_id' => $someCompany->id
            ]);
            $lineItems = explode('_', $order);
            foreach ($lineItems as $lineItem) {
                $requestName = 'PR_' . $lineItem;
                factory(\App\LineItem::class)->create([
                    'purchase_request_id' => $$requestName->id,
                    'purchase_order_id' => $$orderName->id
                ]);
            }
        }

        // we start off with all = $orders array length
        $this->assertCount(7, CompanyPurchaseOrdersRepository::forCompany($someCompany)->getWithoutQueryProperties());

        $projectsCount = [
            'A' => 4,
            'B' => 5,
            'C' => 3
        ];

        foreach ($projectsCount as $proj => $count) {
            $varName = 'proj_' . $proj;
            $this->assertCount($count, CompanyPurchaseOrdersRepository::forCompany($someCompany)->hasRequestForProject(${$varName}->id)->getWithoutQueryProperties());
        }

    }


}

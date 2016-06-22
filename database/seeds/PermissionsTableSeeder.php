<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    protected $permissions = [
        'project_manage' => 'Add & Remove Projects',
        'team_manage' => 'Manage Team',
        'vendor_manage' => 'Edit Vendors',
        'pr_make' => 'Make Purchase Requests',
        'po_submit' => 'Submit Purchase Orders',
        'po_payments' => 'Mark Line Items - Paid',
        'po_warehousing' => 'Mark Line Items - Received',
        'report_view' => 'View Reports',
        'settings_change' => 'Change Settings'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();
        foreach ($this->permissions as $name => $label) {
            Permission::create([
                'name' => $name,
                'label' => $label
            ]);
        }
    }
}

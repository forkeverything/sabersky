<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    protected $permissions = [
        'project_manage' => 'Start & Stop Projects',
        'pr_make' => 'Make or Cancel Purchase Request',
        'po_submit' => 'Submit or Remove Purchase Orders',
        'team_manage' => 'Manage (Add/Remove) All Team Member Roles',
        'report_view' => 'View Reports',
        'buyer_manage' => 'Manage Buyers for a Team',
        'po_payments' => 'Handle Payments for Purchase Orders',
        'po_warehousing' => 'Handle Warehousing for Line Items',
        'settings_change' => 'Change System Settings'
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

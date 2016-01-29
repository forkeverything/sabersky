<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

    // Role and permissions
    protected $acl = [
        'director' => [
            'project_manage',
            'pr_make',
            'po_submit',
            'team_manage',
            'report_view',
            'settings_view',
            'settings_update'
        ],
        'planner' => [
            'pr_make'
        ],
        'manager' => [
            'po_submit',
            'buyer_manage'
        ],
        'buyer' => [
            'po_submit'
        ],
        'cashier' => [
            'po_payments'
        ],
        'technician' => [
            'po_warehousing'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->truncate();
        // Assign permissions to each role
        foreach ($this->acl as $role => $permissions) {
            $role = \App\Role::wherePosition($role)->firstOrFail();
            foreach ($permissions as $permission) {
                $permission = \App\Permission::whereName($permission)->firstOrFail();
                $role->givePermissionTo($permission);
            }
        }
    }
}

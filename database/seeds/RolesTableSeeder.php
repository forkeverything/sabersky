<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * The various roles a user can fill.
     *
     * @var array
     */
    protected $roles = [
        'director', 'planner', 'manager', 'buyer', 'cashier', 'technician'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        foreach ($this->roles as $role) {
            DB::table('roles')->insert(
                ['position' => $role]
            );
        }
    }
}

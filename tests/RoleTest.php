<?php

use App\Company;
use App\Role;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gives_admin_permissions()
    {
        $admin = Role::create([
            'position' => 'admin',
            'company_id' => factory(Company::class)->create()->id
        ]);
        $this->assertEmpty(Role::find($admin->id)->permissions->all());
        $admin->giveAdminPermissions();
        $this->assertNotEmpty(Role::find($admin->id)->permissions->all());
    }
}

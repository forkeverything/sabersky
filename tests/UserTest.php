<?php

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_makes_a_new_user()
    {
        $name = 'foofoo';
        $email = 'you@example.com';
        $password = 'password';

        $this->assertEmpty(User::where('email', $email)->first());
        User::make($name, $email, $password);
        $this->assertEquals('foofoo', User::where('email', $email)->first()->name);
    }
    /** @test */
    public function it_sets_a_user_role()
    {
        $userID = factory(User::class)->create()->id;
        $roleID = factory(Role::class)->create()->id;
        $this->assertNotEquals(User::find($userID)->role_id, Role::find($roleID)->id);
        User::find($userID)->setRole(Role::find($roleID));
        $this->assertEquals(User::find($userID)->role_id, Role::find($roleID)->id);
    }
}

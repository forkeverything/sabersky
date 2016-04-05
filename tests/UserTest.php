<?php

namespace App;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends \TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_makes_a_new_user()
    {
        $name = 'foofoo';
        $email = 'you@example.com';
        $password = 'password';
        $roleId = factory(Role::class)->create()->id;

        $this->assertEmpty(User::where('email', $email)->first());
        $user = User::make($name, $email, $password, $roleId, true);
        $this->assertEquals('foofoo', User::where('email', $email)->first()->name);
        $this->assertEquals($roleId, $user->role_id);
        $this->assertNotNull($user->invite_key);
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

    /** @test */
    public function it_finds_a_user_from_invite_key()
    {
        $user = factory(User::class)->create([
            'invite_key' => 'foobar'
        ]);
        $this->assertEquals($user->id, User::fetchFromInviteKey('foobar')->id);
    }

    /** @test */
    public function it_sets_as_password()
    {
        $user = factory(User::class)->create();
        $this->assertNotEquals('foobar', $user->password);
        $user->setPassword('foobar');
        $this->assertEquals('foobar', $user->password);
    }

    /** @test */
    public function it_clears_invite_key()
    {
        $user = factory(User::class)->create([
            'invite_key' => 'somekey'
        ]);
        $this->assertNotNull($user->invite_key);
        $user->clearInviteKey();
        $this->assertNull($user->invite_key);
    }
}

// Over-write global functions
function bcrypt($string)
{
    return $string;
}

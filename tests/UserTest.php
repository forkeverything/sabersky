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

    /**
     * @test
     */
    public function it_checks_if_user_is_pending()
    {
        $active = factory(User::class)->create();
        $pending = factory(User::class)->create(['invite_key' => str_random(10)]);

        $this->assertFalse($active->isPending());
        $this->assertTrue($pending->isPending());
    }

    /**
     * @test
     */
    public function it_gets_number_of_requests()
    {
        $user = factory(User::class)->create();
        $this->assertEquals(0, $user->num_requests);
        for ($i = 0; $i < 5; $i++) {
            factory(PurchaseRequest::class)->create([
                'user_id' => $user->id
            ]);
        }
        $this->assertEquals(5, User::find($user->id)->num_requests);
    }

    /**
     * @test
     */
    public function it_gets_num_orders()
    {
        $user = factory(User::class)->create();
        $this->assertEquals(0, $user->num_orders);
        for ($i = 0; $i < 3; $i++) {
            factory(PurchaseOrder::class)->create([
                'user_id' => $user->id
            ]);
        }
        $this->assertEquals(3, User::find($user->id)->num_orders);
    }

    /**
     * @test
     */
    public function it_gets_correct_status()
    {
        $cases = [
            [
                'status' => 'pending',
                'invite_key' => str_random(10),
                'active' => 1
            ],
            [
                'status' => 'active',
                'invite_key' => null,
                'active' => 1
            ],
            [
                'status' => 'inactive',
                'invite_key' => null,
                'active' => 0
            ],
        ];

        foreach ($cases as $case) {
            $user = factory(User::class)->create([
                'active' => $case['active'],
                'invite_key' => $case['invite_key']
            ]);

            $this->assertEquals($case['status'], $user->status);
        }
    }

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

    /**
     * @test
     */
    public function it_toggles_user_active()
    {
        $user = factory(User::class)->create(['active' => 1]);
        $user->toggleActive();
        $this->assertEquals(0, User::find($user->id)->active);
        $user->toggleActive();
        $this->assertEquals(1, User::find($user->id)->active);
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

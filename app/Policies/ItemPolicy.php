<?php

namespace App\Policies;

use App\Item;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * User can view item if they belong to same company.
     *
     * @param User $user
     * @param Item $item
     * @return bool
     */
    public function view(User $user, Item $item)
    {
        return $user->company_id == $item->company_id;
    }

    /**
     * User can edit if they belong to same company. Currently, if a user
     * can view an item, they can also edit it.
     *
     * TODO ::: consider stricter requirements to edit an Item
     *
     * @param User $user
     * @param Item $item
     * @return bool
     */
    public function edit(User $user, Item $item)
    {
        return $this->view($user, $item);
    }

}

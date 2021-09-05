<?php

namespace App\Policies;

use App\Models\Home;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     * @throws \Exception
     */
    public function viewAny(User $user)
    {
        return $user->isLandlord() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Home $home
     * @return \Illuminate\Auth\Access\Response|bool
     * @throws \Exception
     */
    public function view(User $user, Home $home)
    {
        return $user->isLandlord() || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $user->isLandlord();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Home  $home
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Home  $home
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Home  $home
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Home  $home
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Home $home)
    {
        //
    }
}

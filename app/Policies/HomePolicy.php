<?php

namespace App\Policies;

use App\Models\Home;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class HomePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return Response|bool
     * @throws \Exception
     */
    public function viewItsOwnHomes(User $user)
    {
        return $user->isLandlord();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param Home $home
     * @return Response|bool
     * @throws \Exception
     */
    public function view(User $user, Home $home)
    {
        return ($user->isOwnerOfHome($home) || $user->isAdmin());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return Response|bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $user->isLandlord();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param Home $home
     * @return Response|bool
     * @throws \Exception
     */
    public function update(User $user, Home $home)
    {
        return $user->isOwnerOfHome($home);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param Home $home
     * @return Response|bool
     */
    public function delete(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param Home $home
     * @return Response|bool
     */
    public function restore(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param Home $home
     * @return Response|bool
     */
    public function forceDelete(User $user, Home $home)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
      @param  \App\Models\User  $user
     * @return Response|bool
     */
    public function viewALlHomes($user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param Home $home
     * @return Response|bool
     * @throws \Exception
     */
    public function viewAHomeAsAdmin(User $user, Home $home)
    {
        return  $user->isAdmin();
    }

}

<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ZoomRoom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZoomRoomPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ZoomRoom');
    }

    public function view(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('View:ZoomRoom');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ZoomRoom');
    }

    public function update(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('Update:ZoomRoom');
    }

    public function delete(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('Delete:ZoomRoom');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ZoomRoom');
    }

    public function restore(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('Restore:ZoomRoom');
    }

    public function forceDelete(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('ForceDelete:ZoomRoom');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ZoomRoom');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ZoomRoom');
    }

    public function replicate(AuthUser $authUser, ZoomRoom $zoomRoom): bool
    {
        return $authUser->can('Replicate:ZoomRoom');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ZoomRoom');
    }

}
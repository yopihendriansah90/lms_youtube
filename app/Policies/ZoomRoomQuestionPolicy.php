<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ZoomRoomQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZoomRoomQuestionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ZoomRoomQuestion');
    }

    public function view(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('View:ZoomRoomQuestion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ZoomRoomQuestion');
    }

    public function update(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('Update:ZoomRoomQuestion');
    }

    public function delete(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('Delete:ZoomRoomQuestion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ZoomRoomQuestion');
    }

    public function restore(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('Restore:ZoomRoomQuestion');
    }

    public function forceDelete(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('ForceDelete:ZoomRoomQuestion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ZoomRoomQuestion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ZoomRoomQuestion');
    }

    public function replicate(AuthUser $authUser, ZoomRoomQuestion $zoomRoomQuestion): bool
    {
        return $authUser->can('Replicate:ZoomRoomQuestion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ZoomRoomQuestion');
    }

}
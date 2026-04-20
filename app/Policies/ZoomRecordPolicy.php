<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ZoomRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZoomRecordPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ZoomRecord');
    }

    public function view(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('View:ZoomRecord');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ZoomRecord');
    }

    public function update(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('Update:ZoomRecord');
    }

    public function delete(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('Delete:ZoomRecord');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ZoomRecord');
    }

    public function restore(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('Restore:ZoomRecord');
    }

    public function forceDelete(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('ForceDelete:ZoomRecord');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ZoomRecord');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ZoomRecord');
    }

    public function replicate(AuthUser $authUser, ZoomRecord $zoomRecord): bool
    {
        return $authUser->can('Replicate:ZoomRecord');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ZoomRecord');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaterialUpdate;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialUpdatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaterialUpdate');
    }

    public function view(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('View:MaterialUpdate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaterialUpdate');
    }

    public function update(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('Update:MaterialUpdate');
    }

    public function delete(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('Delete:MaterialUpdate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MaterialUpdate');
    }

    public function restore(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('Restore:MaterialUpdate');
    }

    public function forceDelete(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('ForceDelete:MaterialUpdate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaterialUpdate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaterialUpdate');
    }

    public function replicate(AuthUser $authUser, MaterialUpdate $materialUpdate): bool
    {
        return $authUser->can('Replicate:MaterialUpdate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaterialUpdate');
    }

}
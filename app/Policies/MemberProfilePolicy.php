<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MemberProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberProfilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MemberProfile');
    }

    public function view(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('View:MemberProfile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MemberProfile');
    }

    public function update(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('Update:MemberProfile');
    }

    public function delete(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('Delete:MemberProfile');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MemberProfile');
    }

    public function restore(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('Restore:MemberProfile');
    }

    public function forceDelete(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('ForceDelete:MemberProfile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MemberProfile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MemberProfile');
    }

    public function replicate(AuthUser $authUser, MemberProfile $memberProfile): bool
    {
        return $authUser->can('Replicate:MemberProfile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MemberProfile');
    }

}
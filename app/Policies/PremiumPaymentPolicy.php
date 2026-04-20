<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PremiumPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PremiumPaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PremiumPayment');
    }

    public function view(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('View:PremiumPayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PremiumPayment');
    }

    public function update(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('Update:PremiumPayment');
    }

    public function delete(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('Delete:PremiumPayment');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PremiumPayment');
    }

    public function restore(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('Restore:PremiumPayment');
    }

    public function forceDelete(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('ForceDelete:PremiumPayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PremiumPayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PremiumPayment');
    }

    public function replicate(AuthUser $authUser, PremiumPayment $premiumPayment): bool
    {
        return $authUser->can('Replicate:PremiumPayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PremiumPayment');
    }

}
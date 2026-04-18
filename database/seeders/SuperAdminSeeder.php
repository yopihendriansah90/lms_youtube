<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => 'superadmin',
            ],
        );

        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            $this->command?->warn(
                'Tabel roles/permissions belum tersedia. User super admin dibuat, tetapi role belum di-assign.'
            );

            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guardName = config('auth.defaults.guard', 'web');

        $role = Role::query()->firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => $guardName,
        ]);

        $permissions = Permission::query()->pluck('name');

        if ($permissions->isNotEmpty()) {
            $role->syncPermissions($permissions);
        }

        $user->syncRoles([$role->name]);
    }
}

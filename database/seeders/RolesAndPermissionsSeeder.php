<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Permissions
    // Permission::create(['name' => 'view dashboard']);
    Permission::create(['name' => 'manage users']);
    Permission::create(['name' => 'edit posts']);
    Permission::create(['name' => 'create posts']);
    Permission::create(['name' => 'delete posts']);
    Permission::create(['name' => 'view posts']);

    // Roles
    $admin = Role::create(['name' => 'admin']);
    $moderator = Role::create(['name' => 'moderator']);
    $user = Role::create(['name' => 'user']);

    // Assign permissions to roles
    $admin->givePermissionTo(Permission::all());

    $user->givePermissionTo(['view posts']);

    $moderator->givePermissionTo(['create posts','view posts']);


    }
}

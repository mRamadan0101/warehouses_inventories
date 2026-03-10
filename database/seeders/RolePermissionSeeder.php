<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $user = Role::where('name', 'user')->first();

        $permissions = Permission::all();

        // Admin has all permissions
        foreach ($permissions as $perm) {
            DB::table('role_permissions')->insert([
                'role_id' => $admin->id,
                'permission_id' => $perm->id,
            ]);
        }

        // Manager has most permissions except manage_users and manage_roles
        $managerPerms = Permission::whereNotIn('name', ['manage_users', 'manage_roles'])->get();
        foreach ($managerPerms as $perm) {
            DB::table('role_permissions')->insert([
                'role_id' => $manager->id,
                'permission_id' => $perm->id,
            ]);
        }

        // User has view permissions
        $userPerms = Permission::where('name', 'like', 'view_%')->get();
        foreach ($userPerms as $perm) {
            DB::table('role_permissions')->insert([
                'role_id' => $user->id,
                'permission_id' => $perm->id,
            ]);
        }
    }
}

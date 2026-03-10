<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create_warehouse', 'slug' => 'create_warehouse']);
        Permission::create(['name' => 'edit_warehouse', 'slug' => 'edit_warehouse']);
        Permission::create(['name' => 'delete_warehouse', 'slug' => 'delete_warehouse']);
        Permission::create(['name' => 'view_warehouse', 'slug' => 'view_warehouse']);
        Permission::create(['name' => 'create_item', 'slug' => 'create_item']);
        Permission::create(['name' => 'edit_item', 'slug' => 'edit_item']);
        Permission::create(['name' => 'delete_item', 'slug' => 'delete_item']);
        Permission::create(['name' => 'view_item', 'slug' => 'view_item']);
        Permission::create(['name' => 'create_stock', 'slug' => 'create_stock']);
        Permission::create(['name' => 'edit_stock', 'slug' => 'edit_stock']);
        Permission::create(['name' => 'delete_stock', 'slug' => 'delete_stock']);
        Permission::create(['name' => 'view_stock', 'slug' => 'view_stock']);
        Permission::create(['name' => 'transfer_stock', 'slug' => 'transfer_stock']);
        Permission::create(['name' => 'manage_users', 'slug' => 'manage_users']);
        Permission::create(['name' => 'manage_roles', 'slug' => 'manage_roles']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Employee permissions
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Activity permissions
            'view activities',
            'create activities',
            'edit activities',
            'delete activities',
            'approve activities',
            'reject activities',
            
            // Asset permissions
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            
            // Loan permissions
            'view loans',
            'create loans',
            'edit loans',
            'delete loans',
            'approve loans',
            'reject loans',
            
            // Document permissions
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'download documents',
            
            // Admin permissions
            'view admin panel',
            'manage users',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view employees',
            'view activities',
            'create activities',
            'edit activities',
            'approve activities',
            'reject activities',
            'view assets',
            'view loans',
            'approve loans',
            'reject loans',
            'view documents',
            'download documents',
            'view admin panel',
        ]);

        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo([
            'view activities',
            'create activities',
            'edit activities',
            'view assets',
            'view loans',
            'create loans',
            'view documents',
            'download documents',
        ]);

        $this->command->info('Permissions and roles created successfully!');
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.manage',
            'inventory.view',
            'inventory.create',
            'inventory.update',
            'inventory.archive',
            'inventory.delete',
            'sales.view',
            'deliveries.manage',
            'reviews.moderate',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $inventoryManager = Role::firstOrCreate(['name' => 'inventory_manager']);
        $salesStaff = Role::firstOrCreate(['name' => 'sales_staff']);
        $deliveryStaff = Role::firstOrCreate(['name' => 'delivery_staff']);
        $feedbackModerator = Role::firstOrCreate(['name' => 'feedback_moderator']);

        // Admin gets everything
        $admin->syncPermissions(Permission::all());

        // Generic staff (legacy) - inventory + sales
        $staff->syncPermissions([
            'inventory.view',
            'inventory.create',
            'sales.view',
        ]);

        // Inventory Manager - full inventory access
        $inventoryManager->syncPermissions([
            'inventory.view',
            'inventory.create',
            'inventory.update',
            'inventory.archive',
            'inventory.delete',
        ]);

        // Sales Staff - sales and orders
        $salesStaff->syncPermissions(['sales.view']);

        // Delivery Staff - manage shipments
        $deliveryStaff->syncPermissions(['deliveries.manage']);

        // Feedback Moderator - review and moderation access
        $feedbackModerator->syncPermissions(['reviews.moderate']);

        // Sync existing admin users (is_admin=true) with admin role
        User::where('is_admin', true)->each(function (User $user) use ($admin) {
            $user->assignRole($admin);
        });
    }
}


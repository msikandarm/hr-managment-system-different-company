<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionGroups = [
            'Pages' => ['show-pages', 'add-page', 'edit-page', 'delete-page', 'update-page-status'],
            'Departments' => ['show-departments', 'add-department', 'edit-department', 'delete-department', 'update-department-status'],
            'Employees' => ['show-employees', 'add-employee', 'edit-employee', 'delete-employee', 'update-employee-status'],
            'Holidays' => ['show-holidays', 'add-holiday', 'edit-holiday', 'delete-holiday', 'update-holiday-status'],
            'Leave Types' => ['show-leave-types', 'add-leave-type', 'edit-leave-type', 'delete-leave-type', 'update-leave-type-status'],
            'Leave Requests' => ['show-leave-requests', 'add-leave-request', 'edit-leave-request', 'delete-leave-request', 'update-leave-request-status'],
            'Settings' => ['show-settings', 'update-settings'],
        ];

        foreach ($permissionGroups as $group => $permissions) {
            $groupPermission = Permission::updateOrCreate(
                ['name' => $group, 'guard_name' => 'web'],
                ['name' => $group]
            );

            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    ['permission_id' => $groupPermission->id, 'name' => $permission, 'guard_name' => 'web'],
                    ['name' => $permission]
                );
            }
        }
    }
}

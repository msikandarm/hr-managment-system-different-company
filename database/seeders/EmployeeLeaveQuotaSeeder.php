<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class EmployeeLeaveQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {
                // Check if quota already exists
                $exists = EmployeeLeaveQuota::where('employee_id', $employee->id)
                    ->where('leave_type_id', $leaveType->id)
                    ->exists();

                if (!$exists) {
                    EmployeeLeaveQuota::create([
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'total_days' => $leaveType->days_allowed, // Default from leave type
                    ]);
                }
            }
        }

        $this->command->info('Employee leave quotas seeded successfully!');
    }
}

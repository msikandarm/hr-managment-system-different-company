<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class EmployeeLeaveQuotaController extends Controller
{
    public function edit(Employee $employee)
    {
        $employee->load(['leaveQuotas.leaveType', 'department']);
        $leaveTypes = LeaveType::where('status', true)->get();

        // Ensure all leave types have quotas for this employee
        foreach ($leaveTypes as $leaveType) {
            $exists = $employee->leaveQuotas()->where('leave_type_id', $leaveType->id)->exists();

            if (!$exists) {
                EmployeeLeaveQuota::create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'total_days' => $leaveType->days_allowed,
                ]);
            }
        }

        // Reload quotas
        $employee->load('leaveQuotas.leaveType');

        return view('admin.employees.leave-quotas', [
            'title' => __('Manage Leave Quotas'),
            'section_title' => __('Employees'),
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'quotas' => ['required', 'array'],
            'quotas.*' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->quotas as $leaveTypeId => $totalDays) {
            EmployeeLeaveQuota::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveTypeId,
                ],
                [
                    'total_days' => $totalDays,
                ]
            );
        }

        return back()->with('success', __('Leave quotas updated successfully.'));
    }
}

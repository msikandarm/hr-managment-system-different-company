<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $approvedLeaveRequests = LeaveRequest::where('status', 'approved')->count();
        $rejectedLeaveRequests = LeaveRequest::where('status', 'rejected')->count();
        $upcomingHolidays = Holiday::where('date', '>=', now())->where('status', true)->count();

        // Get leave requests and holidays for calendar, merge as models first
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->get();
        $holidays = Holiday::where('status', true)->get();
        $employees = Employee::whereNotNull('birthday')->get();

        $calendarEvents = $leaveRequests->merge($holidays)->merge($employees)->map(function ($item) {
            if ($item instanceof LeaveRequest) {
                $color = match($item->status) {
                    'approved' => '#28a745',
                    'rejected' => '#dc3545',
                    default => '#ffc107',
                };

                return [
                    'id' => $item->id,
                    'title' => $item->employee->name . ' - ' . $item->leaveType->title,
                    'start' => $item->start_date->format('Y-m-d'),
                    'end' => $item->end_date->addDay()->format('Y-m-d'),
                    'color' => $color,
                    'url' => route('admin.leave-requests.show', $item->id),
                ];
            }

            if ($item instanceof Employee) {
                // Show birthday for current year
                $birthday = \Carbon\Carbon::parse($item->birthday);
                $currentYear = now()->year;
                $birthdayThisYear = $birthday->copy()->year($currentYear);

                return [
                    'id' => 'birthday-' . $item->id,
                    'title' => '🎉 ' . $item->name . ' Birthday',
                    'start' => $birthdayThisYear->format('Y-m-d'),
                    'color' => '#e83e8c',
                    'allDay' => true,
                ];
            }

            // Holiday model
            return [
                'id' => 'holiday-' . $item->id,
                'title' => $item->title,
                'start' => $item->date->format('Y-m-d'),
                'color' => '#17a2b8',
                'allDay' => true,
            ];
        });

        return view('admin.dashboard', [
            'title' => __('Dashboard'),
            'totalEmployees' => $totalEmployees,
            'totalDepartments' => $totalDepartments,
            'pendingLeaveRequests' => $pendingLeaveRequests,
            'approvedLeaveRequests' => $approvedLeaveRequests,
            'rejectedLeaveRequests' => $rejectedLeaveRequests,
            'upcomingHolidays' => $upcomingHolidays,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}

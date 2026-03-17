<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-leave-requests')->only(['index', 'show']);
        $this->middleware('can:add-leave-request')->only(['create', 'store']);
        $this->middleware('can:edit-leave-request')->only(['edit', 'update']);
        $this->middleware('can:delete-leave-request')->only(['destroy']);
    }

    public function index()
    {
        return view('admin.leave-requests.index', [
            'title' => __('Leave Requests'),
        ]);
    }

    public function create()
    {
        return view('admin.leave-requests.add', [
            'title' => __('Add Leave Request'),
            'section_title' => __('Leave Requests'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee' => ['required', 'exists:employees,id'],
            'leave_type' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'total_days' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ]);

        (new LeaveRequestService)->create($request);

        return to_route('admin.leave-requests.index')->with('success', __('Record added successfully.'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'leaveType']);

        return view('admin.leave-requests.show', [
            'title' => __('Leave Request Details'),
            'section_title' => __('Leave Requests'),
            'row' => $leaveRequest,
        ]);
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        return view('admin.leave-requests.edit', [
            'title' => __('Edit Leave Request'),
            'section_title' => __('Leave Requests'),
            'row' => $leaveRequest,
        ]);
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'employee' => ['required', 'exists:employees,id'],
            'leave_type' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'total_days' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ]);

        (new LeaveRequestService)->update($request, $leaveRequest);

        return back()->with('success', __('Record updated successfully.'));
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'admin_remarks' => ['nullable', 'string'],
        ]);

        (new LeaveRequestService)->updateStatus($request, $leaveRequest);

        return back()->with('success', __('Status updated successfully.'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return back()->with('success', __('Record deleted successfully.'));
    }

    public function getLeaveBalance(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
        ]);

        $employee = \App\Models\Employee::find($request->employee_id);
        $leaveBalance = $employee->getLeaveBalance($request->leave_type_id);

        return response()->json($leaveBalance);
    }
}

<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestService
{
    public function create(Request|array $request): LeaveRequest
    {
        $leaveRequest = new LeaveRequest;

        $leaveRequest->employee_id = $request['employee'];
        $leaveRequest->leave_type_id = $request['leave_type'];
        $leaveRequest->start_date = $request['start_date'];
        $leaveRequest->end_date = $request['end_date'];
        $leaveRequest->total_days = $request['total_days'];
        $leaveRequest->reason = $request['reason'] ?? null;
        $leaveRequest->status = 'pending';

        $leaveRequest->save();

        return $leaveRequest;
    }

    public function update(Request|array $request, LeaveRequest $leaveRequest): LeaveRequest
    {
        $leaveRequest->employee_id = $request['employee'];
        $leaveRequest->leave_type_id = $request['leave_type'];
        $leaveRequest->start_date = $request['start_date'];
        $leaveRequest->end_date = $request['end_date'];
        $leaveRequest->total_days = $request['total_days'];
        $leaveRequest->reason = $request['reason'] ?? null;

        $leaveRequest->save();

        return $leaveRequest;
    }

    public function updateStatus(Request|array $request, LeaveRequest $leaveRequest): LeaveRequest
    {
        $leaveRequest->status = $request['status'];
        $leaveRequest->admin_remarks = $request['admin_remarks'] ?? null;

        $leaveRequest->save();

        return $leaveRequest;
    }
}

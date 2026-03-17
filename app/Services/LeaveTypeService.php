<?php

namespace App\Services;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeService
{
    public function create(Request|array $request): LeaveType
    {
        $leaveType = new LeaveType;

        $leaveType->title = $request['title'];
        $leaveType->days_allowed = $request['days_allowed'];
        $leaveType->description = $request['description'] ?? null;

        $leaveType->save();

        return $leaveType;
    }

    public function update(Request|array $request, LeaveType $leaveType): LeaveType
    {
        $leaveType->title = $request['title'];
        $leaveType->days_allowed = $request['days_allowed'];
        $leaveType->description = $request['description'] ?? null;

        $leaveType->save();

        return $leaveType;
    }
}

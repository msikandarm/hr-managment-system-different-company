<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Services\LeaveTypeService;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-leave-types')->only(['index', 'show']);
        $this->middleware('can:add-leave-type')->only(['create', 'store']);
        $this->middleware('can:edit-leave-type')->only(['edit', 'update']);
        $this->middleware('can:delete-leave-type')->only(['destroy']);
    }
    
    public function index()
    {
        return view('admin.leave-types.index', [
            'title' => __('Leave Types'),
        ]);
    }

    public function create()
    {
        return view('admin.leave-types.add', [
            'title' => __('Add Leave Type'),
            'section_title' => __('Leave Types'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'days_allowed' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        (new LeaveTypeService)->create($request);

        return to_route('admin.leave-types.index')->with('success', __('Record added successfully.'));
    }

    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave-types.edit', [
            'title' => __('Edit Leave Type'),
            'section_title' => __('Leave Types'),
            'row' => $leaveType,
        ]);
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'days_allowed' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        (new LeaveTypeService)->update($request, $leaveType);

        return back()->with('success', __('Record updated successfully.'));
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return back()->with('success', __('Record deleted successfully.'));
    }
}

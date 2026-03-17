<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-departments')->only(['index', 'show']);
        $this->middleware('can:add-department')->only(['create', 'store']);
        $this->middleware('can:edit-department')->only(['edit', 'update']);
        $this->middleware('can:delete-department')->only(['destroy']);
    }

    public function index()
    {
        return view('admin.departments.index', [
            'title' => __('Departments'),
        ]);
    }

    public function create()
    {
        return view('admin.departments.add', [
            'title' => __('Add Department'),
            'section_title' => __('Departments'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
        ]);

        (new DepartmentService)->create($request);

        return to_route('admin.departments.index')->with('success', __('Record added successfully.'));
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', [
            'title' => __('Edit Department'),
            'section_title' => __('Departments'),
            'row' => $department,
        ]);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
        ]);

        (new DepartmentService)->update($request, $department);

        return back()->with('success', __('Record updated successfully.'));
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('success', __('Record deleted successfully.'));
    }
}

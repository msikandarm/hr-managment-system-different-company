<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-employees')->only(['index', 'show', 'offboarded']);
        $this->middleware('can:add-employee')->only(['create', 'store']);
        $this->middleware('can:edit-employee')->only(['edit', 'update', 'restore']);
        $this->middleware('can:delete-employee')->only(['destroy', 'forceDelete']);
    }


    public function index()
    {
        return view('admin.employees.index', [
            'title' => __('Employees'),
        ]);
    }

    public function create()
    {
        return view('admin.employees.add', [
            'title' => __('Add Employee'),
            'section_title' => __('Employees'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'department' => ['required', 'exists:departments,id'],
            'position' => ['required', 'string', 'max:150'],
            'birthday' => ['nullable', 'date'],
        ]);

        (new EmployeeService)->create($request);

        return to_route('admin.employees.index')->with('success', __('Record added successfully.'));
    }

    public function show(Employee $employee)
    {
        $employee->load('department');

        return view('admin.employees.show', [
            'title' => __('Employee Details'),
            'section_title' => __('Employees'),
            'row' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', [
            'title' => __('Edit Employee'),
            'section_title' => __('Employees'),
            'row' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'department' => ['required', 'exists:departments,id'],
            'position' => ['required', 'string', 'max:150'],
            'birthday' => ['nullable', 'date'],
        ]);

        (new EmployeeService)->update($request, $employee);

        return back()->with('success', __('Record updated successfully.'));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return back()->with('success', __('Employee offboarded successfully.'));
    }

    public function offboarded()
    {
        return view('admin.employees.offboarded', [
            'title' => __('Offboarded Employees'),
        ]);
    }

    public function restore($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        return back()->with('success', __('Employee restored successfully.'));
    }

    public function forceDelete($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->forceDelete();

        return back()->with('success', __('Employee permanently deleted.'));
    }
}

<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeService
{
    public function create(Request|array $request): Employee
    {
        $employee = new Employee;

        $employee->name = $request['name'];
        $employee->email = $request['email'];
        $employee->department_id = $request['department'];
        $employee->position = $request['position'];
        $employee->hire_date = $request['hire_date'];
        $employee->birthday = $request['birthday'] ?? null;


        $employee->save();

        $employee->attachGalleryToModelFromRequest('gallery')->toMediaCollection('documents');


        return $employee;
    }

    public function update(Request|array $request, Employee $employee): Employee
    {
        $employee->name = $request['name'];
        $employee->email = $request['email'];
        $employee->department_id = $request['department'];
        $employee->position = $request['position'];
        $employee->hire_date = $request['hire_date'];
        $employee->birthday = $request['birthday'] ?? null;

        $employee->save();

        return $employee;
    }
}

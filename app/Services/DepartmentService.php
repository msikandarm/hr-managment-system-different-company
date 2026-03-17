<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentService
{
    public function create(Request|array $request): Department
    {
        $department = new Department;

        $department->title = $request['title'];

        $department->save();


        return $department;
    }

    public function update(Request|array $request, Department $department): Department
    {
        $department->title = $request['title'];

        $department->save();

        return $department;
    }
}

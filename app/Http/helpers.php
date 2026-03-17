<?php

use App\Helpers\CacheHelper;
use App\Helpers\ResponseHelper;
use App\Models\Category;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Role;

if (! function_exists('phone_format')) {
    function phone_format(?string $number = null)
    {
        $number = str_replace(' ', '', $number);
        $number = str_replace('(', '', $number);
        $number = str_replace(')', '', $number);
        $number = str_replace('-', '', $number);
        $number = str_replace('.', '', $number);

        return $number;
    }
}

if (! function_exists('ps_cache')) {
    function ps_cache(): CacheHelper
    {
        return app('ps_cache');
    }
}

if (! function_exists('ps_response')) {
    function ps_response(): ResponseHelper
    {
        return app('ps_response');
    }
}

if (! function_exists('user_roles')) {
    function user_roles()
    {
        $cache = ps_cache()->get('user_roles');

        if ($cache) {
            return $cache['data'];
        }

        $roles = Role::whereGuardName('web')->orderBy('name')->get();

        ps_cache()->remember()->put('user_roles', $roles);

        return $roles;
    }
}

if (! function_exists('departments')) {
    function departments()
    {
        $cache = ps_cache()->get('departments');

        if ($cache) {
            return $cache['data'];
        }

        $departments = Department::whereStatus(true)->get();

        ps_cache()->put('departments', $departments);

        return $departments;
    }
}

if (! function_exists('categories')) {
    function categories()
    {
        $categories = Category::get();

        return $categories;
    }
}

if (! function_exists('employees')) {
    function employees()
    {
        $cache = ps_cache()->get('employees');

        if ($cache) {
            return $cache['data'];
        }

        $employees = Employee::orderBy('name')->get();

        ps_cache()->put('employees', $employees);

        return $employees;
    }
}

if (! function_exists('leaveTypes')) {
    function leaveTypes()
    {
        $cache = ps_cache()->get('leave_types');

        if ($cache) {
            return $cache['data'];
        }

        $leaveTypes = LeaveType::whereStatus(true)->get();

        ps_cache()->put('leave_types', $leaveTypes);

        return $leaveTypes;
    }
}

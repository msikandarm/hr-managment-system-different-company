<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayService
{
    public function create(Request|array $request): Holiday
    {
        $holiday = new Holiday;

        $holiday->title = $request['title'];
        $holiday->date = $request['date'];
        $holiday->description = $request['description'] ?? null;

        $holiday->save();

        return $holiday;
    }

    public function update(Request|array $request, Holiday $holiday): Holiday
    {
        $holiday->title = $request['title'];
        $holiday->date = $request['date'];
        $holiday->description = $request['description'] ?? null;

        $holiday->save();

        return $holiday;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-holidays')->only(['index', 'show']);
        $this->middleware('can:add-holiday')->only(['create', 'store']);
        $this->middleware('can:edit-holiday')->only(['edit', 'update']);
        $this->middleware('can:delete-holiday')->only(['destroy']);
    }

    public function index()
    {
        $holidays = Holiday::orderBy('date', 'asc')->get();

        return view('admin.holidays.index', [
            'title' => __('Holidays'),
            'holidays' => $holidays,
        ]);
    }

    public function create()
    {
        return view('admin.holidays.add', [
            'title' => __('Add Holiday'),
            'section_title' => __('Holidays'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        (new HolidayService)->create($request);

        return to_route('admin.holidays.index')->with('success', __('Record added successfully.'));
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', [
            'title' => __('Edit Holiday'),
            'section_title' => __('Holidays'),
            'row' => $holiday,
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        (new HolidayService)->update($request, $holiday);

        return back()->with('success', __('Record updated successfully.'));
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('success', __('Record deleted successfully.'));
    }
}

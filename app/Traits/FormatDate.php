<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait FormatDate
{
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('M j, Y'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('M j, Y'),
        );
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('M j, Y') : '',
        );
    }

    protected function deadline(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('M j, Y g:i A'),
        );
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('M j, Y') : '',
        );
    }

     protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('M j, Y') : '',
        );
    }
}

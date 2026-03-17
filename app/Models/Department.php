<?php

namespace App\Models;

use App\Events\ClearCache;
use App\Traits\FormatDate;
use App\Traits\HasSortOrder;
use App\Traits\HasUserCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use FormatDate, HasFactory, SoftDeletes;

    protected $attributes = [
        'status' => true,
    ];

    protected function casts(): array
    {
        return [
            'status' => 'bool',
        ];
    }

    protected $dispatchesEvents = [
        'saved' => ClearCache::class,
        'deleted' => ClearCache::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function holidayRequests()
    {
        return $this->hasMany(HolidayRequest::class);
    }
}

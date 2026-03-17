<?php

namespace App\Models;

use App\Events\ClearCache;
use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

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

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}

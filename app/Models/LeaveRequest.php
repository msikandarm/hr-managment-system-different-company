<?php

namespace App\Models;

use App\Events\ClearCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $dispatchesEvents = [
        'saved' => ClearCache::class,
        'deleted' => ClearCache::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }
}

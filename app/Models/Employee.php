<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TahirRasheed\MediaLibrary\Traits\HasMedia;

class Employee extends Model
{
    use HasFactory, HasMedia, SoftDeletes;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveQuotas()
    {
        return $this->hasMany(EmployeeLeaveQuota::class);
    }

    /**
     * Get total leaves allowed for a specific leave type (from employee's individual quota)
     */
    public function getTotalLeaves($leaveTypeId)
    {
        $quota = $this->leaveQuotas()->where('leave_type_id', $leaveTypeId)->first();

        if ($quota) {
            return $quota->total_days;
        }

        // If no quota set, use default from leave type
        $leaveType = LeaveType::find($leaveTypeId);
        return $leaveType ? $leaveType->days_allowed : 0;
    }

    /**
     * Get total approved leaves taken for a specific leave type
     */
    public function getLeavesTaken($leaveTypeId)
    {
        return $this->leaveRequests()
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->sum('total_days');
    }

    /**
     * Get remaining leaves for a specific leave type
     */
    public function getRemainingLeaves($leaveTypeId)
    {
        $total = $this->getTotalLeaves($leaveTypeId);
        $taken = $this->getLeavesTaken($leaveTypeId);
        return $total - $taken;
    }

    /**
     * Get leave balance information for a specific leave type
     */
    public function getLeaveBalance($leaveTypeId)
    {
        return [
            'total' => $this->getTotalLeaves($leaveTypeId),
            'taken' => $this->getLeavesTaken($leaveTypeId),
            'remaining' => $this->getRemainingLeaves($leaveTypeId),
        ];
    }

    /**
     * Check if employee is on probation (less than 6 months)
     */
    public function isOnProbation()
    {
        $hireDate = \Carbon\Carbon::parse($this->hire_date);
        $monthsEmployed = $hireDate->diffInMonths(now());
        return $monthsEmployed < 6;
    }

    /**
     * Get months employed
     */
    public function getMonthsEmployed()
    {
        $hireDate = \Carbon\Carbon::parse($this->hire_date);
        return $hireDate->diffInMonths(now());
    }

    /**
     * Get probation status badge class
     */
    public function getProbationBadgeClass()
    {
        return $this->isOnProbation() ? 'warning' : 'success';
    }

    /**
     * Get probation status text
     */
    public function getProbationStatus()
    {
        return $this->isOnProbation() ? 'Probation' : 'Confirmed';
    }
}

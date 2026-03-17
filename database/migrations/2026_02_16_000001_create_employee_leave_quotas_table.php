<?php

use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_leave_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(LeaveType::class)->constrained()->cascadeOnDelete();
            $table->integer('total_days')->default(0);
            $table->timestamps();

            // Ensure one quota per employee per leave type
            $table->unique(['employee_id', 'leave_type_id']);
            $table->index('employee_id');
            $table->index('leave_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_quotas');
    }
};

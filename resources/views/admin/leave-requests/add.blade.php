@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-6 col-lg-7 col-md-8">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item url="{{ route('admin.leave-requests.index') }}" label="{{ $section_title }}" />
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <x-button-back href="{{ route('admin.leave-requests.index') }}" />
    </x-slot>

    <x-form action="{{ route('admin.leave-requests.store') }}" method="post">
      <x-form-group label="{{ __('Employee') }}" inputId="employee">
        <x-select name="employee" id="employee" required>
          <option value="" disabled selected>{{ __('Select Employee') }}</option>
          @foreach (employees() as $employee)
            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
          @endforeach
        </x-select>
      </x-form-group>

      <x-form-group label="{{ __('Leave Type') }}" inputId="leave_type">
        <x-select name="leave_type" id="leave_type" required>
          <option value="" disabled selected>{{ __('Select Leave Type') }}</option>
          @foreach (leaveTypes() as $leaveType)
            <option value="{{ $leaveType->id }}">{{ $leaveType->title }} ({{ $leaveType->days_allowed }} days)</option>
          @endforeach
        </x-select>
      </x-form-group>

      <!-- Leave Balance Information -->
      <div id="leave-balance-info" class="alert alert-info" style="display: none;">
        <h6 class="mb-2"><strong>{{ __('Leave Balance') }}:</strong></h6>
        <div class="row">
          <div class="col-md-4">
            <p class="mb-1"><strong>{{ __('Total Leaves') }}:</strong> <span id="total-leaves">0</span></p>
          </div>
          <div class="col-md-4">
            <p class="mb-1"><strong>{{ __('Leaves Taken') }}:</strong> <span id="leaves-taken">0</span></p>
          </div>
          <div class="col-md-4">
            <p class="mb-1"><strong>{{ __('Remaining Leaves') }}:</strong> <span id="remaining-leaves" class="text-success fw-bold">0</span></p>
          </div>
        </div>
      </div>

      <x-form-group label="{{ __('Start Date') }}" inputId="start_date">
        <x-date-picker name="start_date" :minDate="null" required />
      </x-form-group>

      <x-form-group label="{{ __('End Date') }}" inputId="end_date">
        <x-date-picker name="end_date" :minDate="null" required />
      </x-form-group>

      <x-form-group label="{{ __('Total Days') }}" inputId="total_days">
        <x-input name="total_days" type="number" min="1" required />
      </x-form-group>

      <x-form-group label="{{ __('Reason') }}" inputId="reason">
        <x-textarea name="reason" rows="3" />
      </x-form-group>

      <x-button-publish />
    </x-form>
  </x-section-container>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const employeeSelect = document.getElementById('employee');
      const leaveTypeSelect = document.getElementById('leave_type');
      const leaveBalanceInfo = document.getElementById('leave-balance-info');
      const totalLeavesSpan = document.getElementById('total-leaves');
      const leavesTakenSpan = document.getElementById('leaves-taken');
      const remainingLeavesSpan = document.getElementById('remaining-leaves');

      function fetchLeaveBalance() {
        const employeeId = employeeSelect.value;
        const leaveTypeId = leaveTypeSelect.value;

        if (employeeId && leaveTypeId) {
          fetch('{{ route('admin.leave-requests.get-leave-balance') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              employee_id: employeeId,
              leave_type_id: leaveTypeId
            })
          })
          .then(response => response.json())
          .then(data => {
            totalLeavesSpan.textContent = data.total;
            leavesTakenSpan.textContent = data.taken;
            remainingLeavesSpan.textContent = data.remaining;

            // Change color based on remaining leaves
            if (data.remaining < 0) {
              remainingLeavesSpan.className = 'text-danger fw-bold';
            } else if (data.remaining === 0) {
              remainingLeavesSpan.className = 'text-warning fw-bold';
            } else {
              remainingLeavesSpan.className = 'text-success fw-bold';
            }

            leaveBalanceInfo.style.display = 'block';
          })
          .catch(error => {
            console.error('Error fetching leave balance:', error);
            leaveBalanceInfo.style.display = 'none';
          });
        } else {
          leaveBalanceInfo.style.display = 'none';
        }
      }

      employeeSelect.addEventListener('change', fetchLeaveBalance);
      leaveTypeSelect.addEventListener('change', fetchLeaveBalance);
    });
  </script>
  @endpush
@endsection

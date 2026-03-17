@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-8 col-lg-9 col-md-10">
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

    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h5 class="card-title">{{ __('Employee Information') }}</h5>
            <p><strong>{{ __('Name') }}:</strong> {{ $row->employee->name }}</p>
            <p><strong>{{ __('Email') }}:</strong> {{ $row->employee->email }}</p>
            <p><strong>{{ __('Department') }}:</strong> {{ $row->employee->department->title ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6">
            <h5 class="card-title">{{ __('Leave Details') }}</h5>
            <p><strong>{{ __('Leave Type') }}:</strong> {{ $row->leaveType->title }}</p>
            <p><strong>{{ __('Start Date') }}:</strong> {{ $row->start_date }}</p>
            <p><strong>{{ __('End Date') }}:</strong> {{ $row->end_date }}</p>
            <p><strong>{{ __('Total Days') }}:</strong> {{ $row->total_days }}</p>
            <p><strong>{{ __('Status') }}:</strong>
              <span class="badge bg-{{ $row->status_badge }}">{{ ucfirst($row->status) }}</span>
            </p>
          </div>
        </div>

        <!-- Leave Balance Information -->
        @php
          $leaveBalance = $row->employee->getLeaveBalance($row->leave_type_id);
        @endphp
        <div class="row mt-4">
          <div class="col-12">
            <div class="alert alert-info">
              <h5 class="mb-3">{{ __('Leave Balance Summary') }}</h5>
              <div class="row">
                <div class="col-md-4">
                  <div class="text-center p-3 bg-white rounded">
                    <h3 class="text-primary mb-1">{{ $leaveBalance['total'] }}</h3>
                    <p class="mb-0 text-muted">{{ __('Total Leaves Allowed') }}</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3 bg-white rounded">
                    <h3 class="text-warning mb-1">{{ $leaveBalance['taken'] }}</h3>
                    <p class="mb-0 text-muted">{{ __('Leaves Taken') }}</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3 bg-white rounded">
                    <h3 class="mb-1 @if($leaveBalance['remaining'] < 0) text-danger @elseif($leaveBalance['remaining'] == 0) text-warning @else text-success @endif">
                      {{ $leaveBalance['remaining'] }}
                    </h3>
                    <p class="mb-0 text-muted">{{ __('Remaining Leaves') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        @if($row->reason)
        <div class="mt-3">
          <h5 class="card-title">{{ __('Reason') }}</h5>
          <p>{{ $row->reason }}</p>
        </div>
        @endif

        @if($row->admin_remarks)
        <div class="mt-3">
          <h5 class="card-title">{{ __('Admin Remarks') }}</h5>
          <p>{{ $row->admin_remarks }}</p>
        </div>
        @endif
      </div>
    </div>

    @if($row->status === 'pending')
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">{{ __('Update Status') }}</h5>
      </div>
      <div class="card-body">
        <x-form action="{{ route('admin.leave-requests.update-status', ['leave_request' => $row]) }}" method="put">
          <x-form-group label="{{ __('Status') }}" inputId="status">
            <x-select name="status" required>
              <option value="pending" @selected($row->status === 'pending')>{{ __('Pending') }}</option>
              <option value="approved" @selected($row->status === 'approved')>{{ __('Approved') }}</option>
              <option value="rejected" @selected($row->status === 'rejected')>{{ __('Rejected') }}</option>
            </x-select>
          </x-form-group>

          <x-form-group label="{{ __('Admin Remarks') }}" inputId="admin_remarks">
            <x-textarea name="admin_remarks" :value="$row->admin_remarks" rows="3" />
          </x-form-group>

          <x-button-save-changes />
        </x-form>
      </div>
    </div>
    @endif
  </x-section-container>
@endsection

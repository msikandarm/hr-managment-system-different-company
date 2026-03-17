@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-8 col-lg-9 col-md-10">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item url="{{ route('admin.employees.index') }}" label="{{ $section_title }}" />
        <x-breadcrumb-item url="{{ route('admin.employees.show', $employee) }}" label="{{ $employee->name }}" />
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <x-button-back href="{{ route('admin.employees.show', $employee) }}" />
    </x-slot>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3">{{ __('Employee Information') }}</h5>
        <p><strong>{{ __('Name') }}:</strong> {{ $employee->name }}</p>
        <p><strong>{{ __('Email') }}:</strong> {{ $employee->email }}</p>
        <p><strong>{{ __('Department') }}:</strong> {{ $employee->department->title ?? 'N/A' }}</p>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">{{ __('Yearly Leave Quotas') }}</h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-4">{{ __('Set individual leave quotas for this employee. These are the total leaves available per year.') }}</p>

        <x-form action="{{ route('admin.employees.leave-quotas.update', $employee) }}" method="put">
          @foreach($employee->leaveQuotas as $quota)
            <x-form-group label="{{ $quota->leaveType->title }}" inputId="quota_{{ $quota->leave_type_id }}">
              <div class="input-group">
                <x-input
                  name="quotas[{{ $quota->leave_type_id }}]"
                  type="number"
                  min="0"
                  :value="$quota->total_days"
                  required
                />
                <span class="input-group-text">{{ __('days') }}</span>
              </div>
              <small class="text-muted">
                {{ __('Used') }}: {{ $employee->getLeavesTaken($quota->leave_type_id) }} {{ __('days') }} |
                {{ __('Remaining') }}: {{ $quota->total_days - $employee->getLeavesTaken($quota->leave_type_id) }} {{ __('days') }}
              </small>
            </x-form-group>
          @endforeach

          <x-button-save-changes />
        </x-form>
      </div>
    </div>
  </x-section-container>
@endsection

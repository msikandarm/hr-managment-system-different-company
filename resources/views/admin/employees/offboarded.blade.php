@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-9 col-lg-10 col-md-10">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
        <i class="las la-arrow-left"></i> {{ __('Back to Active Employees') }}
      </a>
    </x-slot>

    <livewire:offboarded-employee-table />
  </x-section-container>
@endsection

@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-12 col-lg-12 col-md-12">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <x-button-add href="{{ route('admin.employees.create') }}" />
    </x-slot>

    <livewire:employee-table />
  </x-section-container>
@endsection
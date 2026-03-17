@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-6 col-lg-7 col-md-8">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item url="{{ route('admin.employees.index') }}" label="{{ $section_title }}" />
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <x-button-back href="{{ route('admin.employees.index') }}" />
    </x-slot>

    <x-form action="{{ route('admin.employees.store') }}" method="post" hasFiles>
      <x-form-group label="{{ __('Name') }}" inputId="name">
        <x-input name="name" autofocus required />
      </x-form-group>

      <x-form-group label="{{ __('Email') }}" inputId="email">
        <x-input name="email" type="email" required />
      </x-form-group>

      <x-form-group label="{{ __('Department') }}" inputId="department_id">
        <x-select name="department" required>
          <option value="" disabled selected>{{ __('Select Department') }}</option>
          @foreach (departments() as $department)
            <option value="{{ $department->id }}">{{ $department->title }}</option>
          @endforeach
        </x-select>
      </x-form-group>

       <x-form-group label="{{ __('Position') }}" inputId="position">
        <x-input name="position" required />
      </x-form-group>

      <x-form-group label="{{ __('Hire Date') }}" inputId="hire_date">
        <x-date-picker name="hire_date" :minDate="null" required />
      </x-form-group>

      <x-form-group label="{{ __('Birthday') }}" inputId="birthday">
        <x-date-picker name="birthday" :minDate="null" />
      </x-form-group>

      <x-form-group label="{{ __('Documents') }}">
        <x-medialibrary-dropzone name="gallery" collection="documents" />
      </x-form-group>

      <x-button-publish />
    </x-form>
  </x-section-container>
@endsection

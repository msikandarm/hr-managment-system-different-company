@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-6 col-lg-7 col-md-8">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item url="{{ route('admin.departments.index') }}" label="{{ $section_title }}" />
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <x-button-back href="{{ route('admin.departments.index') }}" />
    </x-slot>

    <x-form action="{{ route('admin.departments.update', ['department' => $row]) }}" method="put">
      <x-form-group label="{{ __('Title') }}" inputId="title">
        <x-input name="title" :value="$row->title" required />
      </x-form-group>

      <x-button-save-changes />
    </x-form>
  </x-section-container>
@endsection

@extends('admin.layouts.app')

@push('header')
<style>
  .holiday-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 25px;
    background: #fff;
  }
  .holiday-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }
  .holiday-card .date-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 20px;
    text-align: center;
  }
  .holiday-card .date-badge .day {
    font-size: 42px;
    font-weight: 700;
    line-height: 1;
  }
  .holiday-card .date-badge .month-year {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 5px;
  }
  .holiday-card .card-body {
    padding: 20px;
  }
  .holiday-card .holiday-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
  }
  .holiday-card .holiday-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
  }
  .holiday-card .status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
  }
  .holiday-card .status-active {
    background: #d4edda;
    color: #155724;
  }
  .holiday-card .status-inactive {
    background: #f8d7da;
    color: #721c24;
  }
  .holiday-card .card-actions {
    border-top: 1px solid #eee;
    padding: 15px 20px;
    display: flex;
    gap: 10px;
  }
  .past-holiday {
    opacity: 0.6;
  }
  .past-holiday .date-badge {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  }
  .upcoming-holiday .date-badge {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  }
</style>
@endpush

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
      <x-button-add href="{{ route('admin.holidays.create') }}" />
    </x-slot>

    @if($holidays->count() > 0)
      <div class="row">
        @foreach($holidays as $holiday)
          @php
            $isPast = $holiday->date->isPast();
            $isUpcoming = $holiday->date->isFuture() && $holiday->date->diffInDays(now()) <= 30;
          @endphp
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="holiday-card {{ $isPast ? 'past-holiday' : ($isUpcoming ? 'upcoming-holiday' : '') }}">
              <div class="date-badge">
                <div class="day">{{ $holiday->date->format('d') }}</div>
                <div class="month-year">{{ $holiday->date->format('M Y') }}</div>
              </div>
              <div class="card-body">
                <h5 class="holiday-title">{{ $holiday->title }}</h5>
                @if($holiday->description)
                  <p class="holiday-description">{{ Str::limit($holiday->description, 80) }}</p>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                  <span class="status-badge {{ $holiday->status ? 'status-active' : 'status-inactive' }}">
                    {{ $holiday->status ? __('Active') : __('Inactive') }}
                  </span>
                  <small class="text-muted">
                    @if($isPast)
                      {{ __('Passed') }}
                    @elseif($holiday->date->isToday())
                      {{ __('Today') }}
                    @else
                      {{ $holiday->date->diffForHumans() }}
                    @endif
                  </small>
                </div>
              </div>
              <div class="card-actions">
                <a href="{{ route('admin.holidays.edit', $holiday) }}" class="btn btn-sm btn-outline-primary">
                  <i class="las la-edit"></i> {{ __('Edit') }}
                </a>
                <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="las la-trash"></i> {{ __('Delete') }}
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="alert alert-info">
        {{ __('No holidays found.') }}
      </div>
    @endif
  </x-section-container>
@endsection

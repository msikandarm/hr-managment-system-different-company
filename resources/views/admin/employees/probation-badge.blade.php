@php
  $hireDate = \Carbon\Carbon::parse($row->hire_date);
  $today = \Carbon\Carbon::now();

  // full months completed
  $months = $hireDate->diffInMonths($today);

  // anchor date = hire date + full months completed
  $anchor = $hireDate->copy()->addMonths($months);

  // remaining days after those full months
  $days = $anchor->diffInDays($today);

  // keep numeric months_employed if needed elsewhere
  $monthsEmployed = $months + ($days / 30);

  // probation: still within first 6 calendar-months (before 6 full months complete)
  $isProbation = $today->lt($hireDate->copy()->addMonths(6));
@endphp


<div class="text-center">
  @if($isProbation)
    <span class="badge bg-warning">
      {{ __('Probation') }}
    </span>
    <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">
      @if($days > 0)
        {{ $months }} {{ $months == 1 ? __('month') : __('months') }} {{ $days }} {{ $days == 1 ? __('day') : __('days') }}
      @else
        {{ $months }} {{ $months == 1 ? __('month') : __('months') }}
      @endif
    </small>
  @else
    <span class="badge bg-success">
     {{ __('Confirmed') }}
    </span>
    <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">
      @if($days > 0)
        {{ $months }} {{ $months == 1 ? __('month') : __('months') }} {{ $days }} {{ $days == 1 ? __('day') : __('days') }}
      @else
        {{ $months }} {{ $months == 1 ? __('month') : __('months') }}
      @endif
    </small>
  @endif
</div>

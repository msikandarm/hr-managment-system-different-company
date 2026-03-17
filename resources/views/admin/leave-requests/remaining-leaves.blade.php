@php
  $remaining = $row->employee->getRemainingLeaves($row->leave_type_id);
  $badgeClass = $remaining < 0 ? 'danger' : ($remaining == 0 ? 'warning' : 'success');
@endphp
<span class="badge bg-{{ $badgeClass }}" title="{{ __('Remaining Leaves') }}">
  {{ $remaining }}
</span>

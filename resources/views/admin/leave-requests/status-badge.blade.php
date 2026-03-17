@php
  $statusClass = match($row->status) {
      'approved' => 'success',
      'rejected' => 'danger',
      default => 'warning',
  };
@endphp
<span class="badge bg-{{ $statusClass }}">{{ ucfirst($row->status) }}</span>

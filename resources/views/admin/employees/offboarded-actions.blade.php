<div class="text-center">
  <form action="{{ route('admin.employees.restore', $row->id) }}" method="POST" class="d-inline">
    @csrf
    @method('PUT')
    <button type="submit" class="btn btn-success btn-sm mb-1" title="{{ __('Restore Employee') }}">
     {{ __('Restore') }}
    </button>
  </form>

  <form action="{{ route('admin.employees.force-delete', $row->id) }}" method="POST" class="d-inline"
        onsubmit="return confirm('{{ __('Are you sure you want to permanently delete this employee? This action cannot be undone!') }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm mb-1" title="{{ __('Permanently Delete') }}">
     {{ __('Permanent Delete') }}
    </button>
  </form>
</div>

<div class="text-center">
  <a class="btn btn-outline-secondary btn-sm mb-1" href="{{ route('admin.employees.show', ['employee' => $row]) }}">{{ __('View') }}</a>
  <a class="btn btn-outline-info btn-sm mb-1" href="{{ route('admin.employees.leave-quotas.edit', ['employee' => $row]) }}" title="{{ __('Manage Leave Quotas') }}">
    {{ __('Leaves') }}
  </a>
  <x-button-edit href="{{ route('admin.employees.edit', ['employee' => $row]) }}" />
  <form action="{{ route('admin.employees.destroy', ['employee' => $row]) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-warning btn-sm mb-1" title="{{ __('Offboard Employee') }}" onclick="return confirm('{{ __('Are you sure you want to offboard this employee?') }}')"> {{ __('Offboard') }}
    </button>
  </form>
</div>
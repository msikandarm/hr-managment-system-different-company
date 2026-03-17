<div class="text-center">
  <x-button-edit href="{{ route('admin.leave-types.edit', ['leave_type' => $row]) }}" />
  <x-button-delete action="{{ route('admin.leave-types.destroy', ['leave_type' => $row]) }}" />
</div>

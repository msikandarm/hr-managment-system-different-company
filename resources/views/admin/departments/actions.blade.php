<div class="text-center">
  <x-button-edit href="{{ route('admin.departments.edit', ['department' => $row]) }}" />
  <x-button-delete action="{{ route('admin.departments.destroy', ['department' => $row]) }}" />
</div>
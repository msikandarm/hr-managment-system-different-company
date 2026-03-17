<div class="text-center">
  <x-button-edit href="{{ route('admin.holidays.edit', ['holiday' => $row]) }}" />
  <x-button-delete action="{{ route('admin.holidays.destroy', ['holiday' => $row]) }}" />
</div>

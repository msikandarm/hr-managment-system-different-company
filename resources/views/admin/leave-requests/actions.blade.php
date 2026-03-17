<div class="text-center">
  <x-button-view href="{{ route('admin.leave-requests.show', ['leave_request' => $row]) }}" />
  <x-button-edit href="{{ route('admin.leave-requests.edit', ['leave_request' => $row]) }}" />
  <x-button-delete action="{{ route('admin.leave-requests.destroy', ['leave_request' => $row]) }}" />
</div>

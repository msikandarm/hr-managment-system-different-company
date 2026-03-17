@extends('admin.layouts.app')

@section('content')
  <x-section-container column="col-xl-9 col-lg-10 col-md-10">
    <x-slot name="title">
      {{ $title }}
    </x-slot>

    <x-slot name="breadcrumbs">
      <x-breadcrumbs url="{{ route('admin.dashboard') }}">
        <x-breadcrumb-item class="active" label="{{ $title }}" />
      </x-breadcrumbs>
    </x-slot>

    <x-slot name="buttons">
      <a href="{{ route('admin.employees.leave-quotas.edit', $row) }}" class="btn btn-primary">
        <i class="fas fa-calendar-check"></i> {{ __('Manage Leave Quotas') }}
      </a>
      <x-button-back href="{{ route('admin.employees.index') }}" />
    </x-slot>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ __('Name') }}</h5>
        <p class="card-text">{{ $row->name }}</p>
        <h5 class="card-title">{{ __('Email') }}</h5>
        <p class="card-text">{{ $row->email }}</p>
        <h5 class="card-title">{{ __('Department') }}</h5>
        <p class="card-text">{{ $row->department->title }}</p>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-body">
        <h5 class="card-title">{{ __('Position') }}</h5>
        <p class="card-text">{{ $row->position }}</p>
        <h5 class="card-title">{{ __('Hire Date') }}</h5>
        <p class="card-text">{{ $row->hire_date }}</p>
        <h5 class="card-title">{{ __('Employment Status') }}</h5>
        <p class="card-text">
          <span class="badge bg-{{ $row->getProbationBadgeClass() }}">
            <i class="fas {{ $row->isOnProbation() ? 'fa-clock' : 'fa-check-circle' }}"></i>
            {{ __($row->getProbationStatus()) }}
          </span>
          <span class="text-muted ms-2">({{ $row->getMonthsEmployed() }} {{ __('months employed') }})</span>
        </p>
      </div>
    </div>

    @if ($row->getAttachments('gallery')->isNotEmpty())
      <div class="card mt-3">
        <div class="card-header">
          <h5 class="mb-0">{{ __('Gallery & Documents') }}</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            @foreach ($row->getAttachments('gallery') as $key => $attachment)
              @php
                $extension = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
                $iconClass = match($extension) {
                  'pdf' => 'fa-file-pdf text-danger',
                  'doc', 'docx' => 'fa-file-word text-primary',
                  'xls', 'xlsx' => 'fa-file-excel text-success',
                  'ppt', 'pptx' => 'fa-file-powerpoint text-warning',
                  'zip', 'rar', '7z' => 'fa-file-zipper text-secondary',
                  'txt' => 'fa-file-lines text-muted',
                  default => 'fa-file text-secondary',
                };
              @endphp

              <div class="col-lg-3 col-md-4 col-sm-6">
                @if($isImage)
                  <!-- Image Thumbnail -->
                  <div class="gallery-item">
                    <img
                      src="{{ $attachment->getUrl() }}"
                      alt="{{ $attachment->file_name }}"
                      class="img-thumbnail gallery-image"
                      id="image-{{ $attachment->id }}"
                      style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                      data-bs-toggle="modal"
                      data-bs-target="#imageModal{{ $attachment->id }}"
                    />
                    <p class="text-center mt-2 mb-0 small text-truncate">{{ $attachment->file_name }}</p>
                  </div>

                  <!-- Modal for full-size image -->
                  <div class="modal fade" id="imageModal{{ $attachment->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">{{ $attachment->file_name }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                          <img
                            src="{{ $attachment->getUrl() }}"
                            alt="{{ $attachment->file_name }}"
                            class="img-fluid"
                            style="max-height: 70vh; object-fit: contain;"
                          />
                        </div>
                        <div class="modal-footer">
                          <a href="{{ $attachment->getUrl() }}" download class="btn btn-primary btn-sm">
                            <i class="fas fa-download"></i> {{ __('Download') }}
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- Document Card -->
                  <div class="document-item card h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center" style="min-height: 200px;">
                      <div class="mb-3">
                        <i class="fas {{ $iconClass }} fa-4x"></i>
                      </div>
                      <h6 class="text-truncate mb-2" title="{{ $attachment->file_name }}">
                        {{ $attachment->file_name }}
                      </h6>
                      <p class="text-muted small mb-3">
                        {{ strtoupper($extension) }}
                        @if($attachment->file_size)
                          • {{ number_format($attachment->file_size / 1024, 2) }} KB
                        @endif
                      </p>
                      <div class="mt-auto">
                        <a href="{{ $attachment->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                          <i class="fas fa-eye"></i> {{ __('View') }}
                        </a>
                        <a href="{{ $attachment->getUrl() }}" download class="btn btn-sm btn-outline-success">
                          <i class="fas fa-download"></i> {{ __('Download') }}
                        </a>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </x-section-container>

  @push('styles')
  <style>
    .gallery-image {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .gallery-image:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .gallery-item {
      overflow: hidden;
      border-radius: 8px;
    }
    .document-item {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: default;
    }
    .document-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .document-item .card-body {
      padding: 1.5rem;
    }
  </style>
  @endpush
@endsection
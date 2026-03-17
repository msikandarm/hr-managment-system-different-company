
@extends('admin.layouts.app')

@push('header')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
  .dashboard-card {
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    color: #fff;
    transition: transform 0.3s ease;
  }
  .dashboard-card:hover {
    transform: translateY(-5px);
  }
  .dashboard-card .card-icon {
    font-size: 40px;
    opacity: 0.8;
  }
  .dashboard-card .card-number {
    font-size: 32px;
    font-weight: 700;
  }
  .dashboard-card .card-title {
    font-size: 14px;
    opacity: 0.9;
  }
  .bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }
  .bg-success-gradient {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  }
  .bg-warning-gradient {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  }
  .bg-info-gradient {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  }
  .bg-danger-gradient {
    background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
  }
  .bg-purple-gradient {
    background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
  }
  #calendar {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
  }
  .fc-event {
    cursor: pointer;
  }
  .legend-item {
    display: inline-flex;
    align-items: center;
    margin-right: 15px;
    font-size: 13px;
  }
  .legend-color {
    width: 15px;
    height: 15px;
    border-radius: 3px;
    margin-right: 5px;
  }
</style>
@endpush

@section('content')
  <div class="body_contents p-0">
    <div class="container-fluid padding-15">
      <h4 class="mb-4">{{ __('Dashboard') }}</h4>

      <!-- Stats Cards -->
      <div class="row">
        <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-primary-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $totalEmployees }}</div>
                <div class="card-title">{{ __('Total Employees') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-users"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-success-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $totalDepartments }}</div>
                <div class="card-title">{{ __('Departments') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-building"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-warning-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $pendingLeaveRequests }}</div>
                <div class="card-title">{{ __('Pending Leaves') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-clock"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-info-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $approvedLeaveRequests }}</div>
                <div class="card-title">{{ __('Approved Leaves') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-check-circle"></i>
              </div>
            </div>
          </div>
        </div>
        {{-- <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-danger-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $rejectedLeaveRequests }}</div>
                <div class="card-title">{{ __('Rejected Leaves') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-times-circle"></i>
              </div>
            </div>
          </div>
        </div> --}}
        <div class="col-xl-2 col-lg-4 col-md-6">
          <div class="dashboard-card bg-purple-gradient">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="card-number">{{ $upcomingHolidays }}</div>
                <div class="card-title">{{ __('Upcoming Holidays') }}</div>
              </div>
              <div class="card-icon">
                <i class="las la-calendar-check"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Calendar Legend -->
      <div class="mb-3 mt-4">
        <div class="legend-item">
          <span class="legend-color" style="background: #ffc107;"></span>
          {{ __('Pending Leave') }}
        </div>
        <div class="legend-item">
          <span class="legend-color" style="background: #28a745;"></span>
          {{ __('Approved Leave') }}
        </div>
        {{-- <div class="legend-item">
          <span class="legend-color" style="background: #dc3545;"></span>
          {{ __('Rejected Leave') }}
        </div> --}}
        <div class="legend-item">
          <span class="legend-color" style="background: #17a2b8;"></span>
          {{ __('Holiday') }}
        </div>
        <div class="legend-item">
          <span class="legend-color" style="background: #e83e8c;"></span>
          {{ __('Birthday') }} 🎉
        </div>
      </div>

      <!-- Full Calendar -->
      <div class="row">
        <div class="col-12">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listMonth'
      },
      events: @json($calendarEvents),
      eventClick: function(info) {
        if (info.event.url) {
          window.location.href = info.event.url;
          info.jsEvent.preventDefault();
        }
      },
      height: 'auto',
      eventDisplay: 'block',
    });
    calendar.render();
  });
</script>
@endpush
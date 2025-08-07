@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'logs'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Attendance Logs</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        <div class="d-flex justify-content-between">
          <a href="{{ route('logs.print') }}" class="btn btn-outline-dark mb-3 w-25" target="_blank">
            <i class="fas fa-print mr-1"></i>
              <span> Print</span>
          </a>
        </div>

        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              <th scope="col" class="table-dark">Employee</th>
              <th scope="col" class="table-dark">Event Type</th>
              <th scope="col" class="table-dark">Status</th>
              <th scope="col" class="table-dark">Description</th>
              <th scope="col" class="table-dark">Details</th>
              <th scope="col" class="table-dark">Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($logs as $log)
            <tr>
              <td>{{ $log->employee ? $log->employee->name : 'N/A' }}</td>
              <td>
                <span class="badge badge-{{ $log->event_type == 'attendance' ? 'primary' : ($log->event_type == 'leave' ? 'warning' : 'secondary') }}">
                  {{ ucfirst($log->event_type) }}
                </span>
              </td>
              <td>
                <span class="badge badge-{{ $log->status == 'present' ? 'success' : ($log->status == 'sick' ? 'danger' : ($log->status == 'approved' ? 'info' : 'warning')) }}">
                  {{ ucfirst($log->status) }}
                </span>
              </td>
              <td class="w-25">{{ $log->description }}</td>
              <td class="w-25">{{ $log->details }}</td>
              <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $logs->links() }}  
      </div>
    </div>
  </div>
</div>
@endsection
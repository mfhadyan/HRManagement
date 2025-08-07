@extends('layouts.print')

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Attendance Logs Report</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              <th scope="col" class="table-dark">#</th>
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
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $log->employee ? $log->employee->name : 'N/A' }}</td>
              <td>{{ ucfirst($log->event_type) }}</td>
              <td>{{ ucfirst($log->status) }}</td>
              <td>{{ $log->description }}</td>
              <td>{{ $log->details }}</td>
              <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('_script')
    <script>
      window.onload = function () {
        window.print();
      }
    </script>
@endsection
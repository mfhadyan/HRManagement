@extends('layouts.print')

@section('_content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="text-center mb-4">
        <h3 class="font-weight-bold">Announcements Report</h3>
        <p class="text-muted">Generated on {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
      </div>
    </div>
  </div>

  @if($announcements->count() > 0)
    @foreach($announcements as $announcement)
      <div class="announcement-item mb-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="announcement-content">
                  {!! nl2br(e($announcement->description)) !!}
                </div>
                
                @if($announcement->attachment)
                  <div class="attachment-info mt-3">
                    <strong>Attachment:</strong> Available for download
                  </div>
                @endif
              </div>
              
              <div class="col-md-4">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td><strong>Department:</strong></td>
                    <td>
                      @if($announcement->department)
                        {{ $announcement->department->name }}
                      @else
                        All Departments
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Created By:</strong></td>
                    <td>{{ $announcement->createdBy->name }}</td>
                  </tr>
                  <tr>
                    <td><strong>Created At:</strong></td>
                    <td>{{ $announcement->formatted_created_at }}</td>
                  </tr>
                  <tr>
                    <td><strong>Updated At:</strong></td>
                    <td>{{ $announcement->formatted_updated_at }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="text-center">
      <h5>No announcements found</h5>
      <p>There are no announcements to display.</p>
    </div>
  @endif
</div>

<style>
.announcement-content {
  font-size: 14px;
  line-height: 1.5;
  white-space: pre-wrap;
}

.announcement-item {
  page-break-inside: avoid;
}

@media print {
  .announcement-item {
    margin-bottom: 20px;
  }
}
</style>
@endsection

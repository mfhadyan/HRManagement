@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'announcements'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Announcement Details</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        
        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
          <h6 class="mb-0">Announcement Information</h6>
          <div>
            @if(auth()->user()->role_id == 1)
              <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>
                Edit
              </a>
              <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="fas fa-trash mr-1"></i>
                  Delete
                </button>
              </form>
            @endif
            <a href="{{ route('announcements') }}" class="btn btn-secondary btn-sm">
              <i class="fas fa-arrow-left mr-1"></i>
              Back to Announcements
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
              </div>
              <div class="card-body">
                <div class="announcement-content">
                  {!! nl2br(e($announcement->description)) !!}
                </div>
                
                @if($announcement->attachment)
                  <hr>
                  <div class="attachment-section">
                    <h6>Attachment:</h6>
                    <a href="{{ asset('/storage/' . $announcement->attachment) }}" target="_blank" class="btn btn-info btn-sm">
                      <i class="fas fa-download mr-1"></i>
                      Download Attachment
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title mb-0">Announcement Details</h6>
              </div>
              <div class="card-body">
                <table class="table table-borderless">
                  <tr>
                    <td><strong>Department:</strong></td>
                    <td>
                      @if($announcement->department)
                        <span class="badge badge-info">{{ $announcement->department->name }}</span>
                      @else
                        <span class="badge badge-secondary">All Departments</span>
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
    </div>
  </div>
</div>

<style>
.announcement-content {
  font-size: 16px;
  line-height: 1.6;
  white-space: pre-wrap;
}

.attachment-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #dee2e6;
}
</style>
@endsection

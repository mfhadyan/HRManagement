@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'announcements'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Announcements</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        
        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
          <h6 class="mb-0">All Announcements</h6>
          <div>
            @if(auth()->user()->role_id == 1)
              <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i>
                Create Announcement
              </a>
            @endif
            <a href="{{ route('announcements.print') }}" class="btn btn-secondary btn-sm" target="_blank">
              <i class="fas fa-print mr-1"></i>
              Print
            </a>
          </div>
        </div>

        @if($announcements->count() > 0)
        <div class="table-responsive">
          <table class="table table-light table-striped table-hover table-bordered text-center">
            <thead>
              <tr>
                <th scope="col" class="table-dark">#</th>
                <th scope="col" class="table-dark">Title</th>
                <th scope="col" class="table-dark">Department</th>
                <th scope="col" class="table-dark">Created By</th>
                <th scope="col" class="table-dark">Created At</th>
                <th scope="col" class="table-dark">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($announcements as $announcement)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                  <a href="{{ route('announcements.show', $announcement) }}" class="text-decoration-none">
                    {{ $announcement->title }}
                  </a>
                </td>
                <td>
                  @if($announcement->department)
                    <span class="badge badge-info">{{ $announcement->department->name }}</span>
                  @else
                    <span class="badge badge-secondary">All Departments</span>
                  @endif
                </td>
                <td>{{ $announcement->createdBy->name }}</td>
                <td>{{ $announcement->formatted_created_at }}</td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-info btn-sm">
                      <i class="fas fa-eye"></i>
                    </a>
                    @if(auth()->user()->role_id == 1)
                      <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
          {{ $announcements->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
          <h5>No announcements found</h5>
          <p>There are no announcements to display.</p>
          @if(auth()->user()->role_id == 1)
            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
              <i class="fas fa-plus mr-1"></i>
              Create First Announcement
            </a>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

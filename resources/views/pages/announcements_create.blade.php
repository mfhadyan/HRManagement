@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'announcements'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Create Announcement</h4>
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
          <h6 class="mb-0">Create New Announcement</h6>
          <a href="{{ route('announcements') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Announcements
          </a>
        </div>

        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Enter announcement title" value="{{ old('title') }}" required>
                @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="8" placeholder="Enter announcement description" required>{{ old('description') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="department_id">Department</label>
                <select class="form-control @error('department_id') is-invalid @enderror" name="department_id" id="department_id">
                  <option value="">All Departments</option>
                  @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                      {{ $department->name }}
                    </option>
                  @endforeach
                </select>
                @error('department_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Leave empty to make this announcement visible to all departments.</small>
              </div>

              <div class="form-group">
                <label for="attachment">Attachment</label>
                <input type="file" class="form-control @error('attachment') is-invalid @enderror" name="attachment" id="attachment">
                @error('attachment')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)</small>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="fas fa-save mr-1"></i>
                  Create Announcement
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

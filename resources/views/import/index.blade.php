<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bulk Task Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Import Failures --}}
                    @if(session('validation_failures'))
                        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Import Validation Failures:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach (session('validation_failures') as $failure)
                                    <li>{{ $failure }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Download Template Section --}}
                    <div class="mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-download me-2"></i>
                                    Download Template
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-3">Download the CSV template to ensure your data is formatted correctly for import.</p>
                                <a href="{{ route('import.template') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-file-csv me-2"></i>
                                    Download CSV Template
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Import Form Section --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-upload me-2"></i>
                                Import Tasks
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="csv_file" class="form-label">
                                        <i class="fas fa-file-upload me-1"></i>
                                        Upload CSV File
                                    </label>
                                    <input 
                                        type="file" 
                                        name="csv_file" 
                                        id="csv_file" 
                                        class="form-control @error('csv_file') is-invalid @enderror"
                                        accept=".csv,.txt"
                                        required
                                    >
                                    @error('csv_file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Accepted formats: .csv, .txt (Max size: 5MB)
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Import Tasks
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Instructions --}}
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-question-circle me-2"></i>
                                Import Instructions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Required Fields:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i><strong>title</strong> - Task title (max 255 characters)</li>
                                    </ul>
                                    
                                    <h6 class="mt-3">Optional Fields:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>description</strong> - Task description</li>
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>category</strong> - Task category</li>
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>priority</strong> - low, medium, high</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Date & Status Fields:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>due_date</strong> - Format: YYYY-MM-DD</li>
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>status</strong> - pending, in_progress, completed</li>
                                        <li><i class="fas fa-circle text-muted me-2"></i><strong>assigned_to</strong> - Valid email address</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
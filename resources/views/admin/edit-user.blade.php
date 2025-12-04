@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <!-- Edit User Form -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User: {{ $user->name }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="juri" {{ old('role', $user->role) == 'juri' ? 'selected' : '' }}>Juri</option>
                                <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (leave empty to keep current)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Leave empty to keep current password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active User
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update User
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- User Information -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">User Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>User ID:</strong> {{ $user->id }}</p>
                    <p><strong>Created:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Never' }}</p>
                    <p><strong>Email Verified:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}</p>
                    <p><strong>Current Status:</strong>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>

            @if($user->profile)
                <hr>
                <h6 class="mb-3">Profile Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> {{ $user->profile->phone ?? '-' }}</p>
                        <p><strong>Address:</strong> {{ $user->profile->address ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($user->role === 'peserta')
                            <p><strong>Participant Number:</strong> {{ $user->profile->nomor_peserta ?? '-' }}</p>
                        @endif
                        @if($user->role === 'juri')
                            <p><strong>Expertise:</strong> {{ $user->profile->keahlian ?? '-' }}</p>
                            <p><strong>Institution:</strong> {{ $user->profile->institusi ?? '-' }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
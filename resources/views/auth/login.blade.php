@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <div class="col-12 col-md-6 mx-auto">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Login Card -->
            <div class="card shadow-lg">
                <div class="card-body">
                    <!-- Logo EtimeWorking -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/images/etimeworking_logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 150px;">
                    </div>

                    <!-- Login Form -->
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email', request()->cookie('remember_email')) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"  id="password" name="password" value="{{ request()->cookie('remember_password') }}" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember me checkbox -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                    {{ request()->cookie('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" onclick="showLoadingSpinner(); this.submit();" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
            <small>&copy; {{ date('Y') }} TFM - Andrés Terol Sánchez. (CC BY-ND).</small>
        </div>
        <div class="container text-center mt-auto py-3">
            <p><a href="https://creativecommons.org/licenses/by-nd/4.0/" target="_blank">
                Creative Commons Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0)
                <img src="{{ asset('assets/images/license.png') }}" alt="Licencia CC BY-ND" class="img-fluid" width="100">
            </a></p>
        </div>
    </div>
</div>
@endsection

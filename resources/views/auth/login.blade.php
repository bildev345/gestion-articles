@extends('layout.app')

@section('content')

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="col-md-5">

        <div class="card login-card p-4">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary-ciel">Login</h2>
                <p class="text-muted">Access your dashboard</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control custom-input" required autofocus>

                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password"
                           class="form-control custom-input" required>

                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center">

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted small">
                            Forgot password?
                        </a>
                    @endif

                    <button type="submit" class="btn btn-primary px-4">
                        Login
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('styles')
<style>
    body{
        background: linear-gradient(135deg, #f8fafc, #eef2ff);
    }

    .text-primary-ciel{
        color: #4e73df;
    }

    /* CARD STYLE */
    .login-card{
        border-radius: 20px;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(12px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.4);
        transition: 0.3s;
    }

    .login-card:hover{
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }

    /* INPUT */
    .custom-input{
        border-radius: 12px;
        padding: 10px;
        border: 1px solid #ddd;
        transition: 0.3s;
    }

    .custom-input:focus{
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.2);
    }

    /* BUTTON */
    .btn-primary{
        background: linear-gradient(135deg, #4e73df, #224abe);
        border: none;
        border-radius: 12px;
    }

    .btn-primary:hover{
        opacity: 0.9;
    }
</style>
@endpush
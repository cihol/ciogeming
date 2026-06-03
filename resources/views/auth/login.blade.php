@extends('layouts.app')

@section('content')

<style>
    body{
        background: #f5f5f5;
        font-family: 'Poppins', sans-serif;
    }

    .login-container{
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card{
        width: 100%;
        max-width: 450px;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .login-header{
        background: #d50000;
        color: white;
        text-align: center;
        padding: 25px;
    }

    .login-header h4{
        margin: 0;
        font-weight: 700;
    }

    .login-header small{
        opacity: 0.9;
    }

    .login-body{
        padding: 30px;
    }

    .form-label{
        color: #555;
        font-weight: 600;
    }

    .form-control{
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus{
        border-color: #d50000;
        box-shadow: 0 0 0 0.2rem rgba(213,0,0,0.15);
    }

    .btn-login{
        background: #d50000;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-login:hover{
        background: #b30000;
        color: white;
    }

    .alert-danger{
        border-radius: 10px;
    }

    .logo-text{
        color: #666;
        text-align: center;
        margin-bottom: 20px;
    }
</style>

<div class="container login-container">
    <div class="login-card">

    <div class="login-header">
    <img src="https://ypt.or.id/wp-content/uploads/2025/02/TS-logo-white-750x241.png"
         alt="Logo Telkom"
         width="220"
         class="mb-2"
         style="margin-left: -10px;">

    <h4>Inventaris TEFA</h4>
    <small>SMK Telkom</small>
</div>

        <div class="login-body">

            <div class="logo-text">
                <p class="mb-2">
                    Sistem Inventaris Peralatan TEFA
                </p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email kamu"
                        required
                        autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    Login
                </button>

            </form>

        </div>

    </div>
</div>

@endsection

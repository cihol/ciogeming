<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inventaris TEFA</title>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand text-primary fw-bold" href="#">Inventaris TEFA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('barang*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('peminjaman*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3 {{ Request::is('peminjam*') ? 'active' : '' }}" href="{{ route('peminjam.index') }}">Peminjam</a>
                    </li>

                    <li class="nav-item">
                        <span class="badge bg-primary me-3 py-2">
                            {{ Auth::user()->name }} ({{ Auth::user()->role }})
                        </span>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
            </div>
    </div>
</nav>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

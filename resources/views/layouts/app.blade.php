<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard.index') }}">Inventario</a>
            <div class="navbar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active fw-semibold' : '' }}" href="{{ route('dashboard.index') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active fw-semibold' : '' }}" href="{{ route('productos.index') }}">Productos</a>
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active fw-semibold' : '' }}" href="{{ route('categorias.index') }}">Categorías</a>
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-semibold' : '' }}" href="{{ route('clientes.index') }}">Clientes</a>
                <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active fw-semibold' : '' }}" href="{{ route('ventas.index') }}">Ventas</a>
            </div>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
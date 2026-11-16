
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Productos</h2>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
</div>

@if($productosBajoStock->isNotEmpty())
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <div>
            <strong>Atención:</strong> Tienes {{ $productosBajoStock->count() }} producto(s) con stock bajo o agotado
            ({{ $productosBajoStock->pluck('nombre')->join(', ') }}).
        </div>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr class="{{ $producto->tieneStockBajo() ? 'table-warning' : '' }}">
                        <td>{{ $producto->id }}</td>
                        <td class="fw-semibold">{{ $producto->nombre }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>
                            @if($producto->stock == 0)
                                <span class="badge bg-danger">Agotado (0)</span>
                            @elseif($producto->tieneStockBajo())
                                <span class="badge bg-warning text-dark">Bajo ({{ $producto->stock }} unid.)</span>
                            @else
                                <span class="badge bg-success">{{ $producto->stock }} unid.</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $producto->stock_minimo }} unid.</td>
                        <td class="text-end">
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay productos registrados todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Clientes -->
<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
    <h2 class="mb-0">Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Nuevo Cliente</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->apellido }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>{{ $cliente->direccion ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay clientes registrados todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $clientes->links() }}
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Dashboard General</h2>
    <a href="{{ route('ventas.index') }}" class="btn btn-primary">+ Nueva Venta</a>
</div>

{{-- Tarjetas de Resumen (KPIs) --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Ventas de Hoy</h6>
                <h3 class="fw-bold mb-1">${{ number_format($ventasHoy, 2) }}</h3>
                <small class="text-muted">{{ $cantidadVentasHoy }} transacciones hoy</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Ingresos del Mes</h6>
                <h3 class="fw-bold mb-1">${{ number_format($ventasMes, 2) }}</h3>
                <small class="text-success">Acumulado mensual</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Alertas de Stock</h6>
                <h3 class="fw-bold mb-1 text-danger">{{ $productosStockBajo->count() }}</h3>
                <small class="text-muted">Productos en mínimo o agotados</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Clientes Registrados</h6>
                <h3 class="fw-bold mb-1">{{ $totalClientes }}</h3>
                <small class="text-muted">Total en base de datos</small>
            </div>
        </div>
    </div>
</div>

{{-- Sección de Gráficos --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Ingresos de los últimos 7 días</div>
            <div class="card-body">
                <canvas id="ventasSemanaChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Top 5 Productos Más Vendidos</div>
            <div class="card-body">
                <canvas id="topProductosChart" height="235"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tablas de Acción Rápida --}}
<div class="row g-3 mb-4">
    {{-- Alertas de Stock Bajo --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold text-danger">Productos con Stock Bajo</span>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">Ver inventario</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosStockBajo as $producto)
                            <tr>
                                <td class="fw-semibold">{{ $producto->nombre }}</td>
                                <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $producto->stock }}</span>
                                </td>
                                <td class="text-center text-muted">{{ $producto->stock_minimo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Todo el inventario tiene stock suficiente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Últimas Ventas --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">Últimas Ventas Realizadas</span>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">Ir a ventas</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasVentas as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->cliente->nombre ?? 'Consumidor Final' }}</td>
                                <td class="text-muted small">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end fw-bold">${{ number_format($venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Aún no hay ventas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico 1: Ventas últimos 7 días
    const ctxVentas = document.getElementById('ventasSemanaChart').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: @json($fechas),
            datasets: [{
                label: 'Ventas ($)',
                data: @json($totalesPorDia),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Gráfico 2: Top 5 productos más vendidos
    const ctxTop = document.getElementById('topProductosChart').getContext('2d');
    new Chart(ctxTop, {
        type: 'bar',
        data: {
            labels: @json($nombresTopProductos),
            datasets: [{
                label: 'Unidades vendidas',
                data: @json($cantidadesTopProductos),
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#6c757d'],
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endpush
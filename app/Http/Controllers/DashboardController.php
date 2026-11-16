<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        // 1. Indicadores principales (KPIs)
        $ventasHoy = Venta::whereDate('created_at', $hoy)->sum('total');
        $cantidadVentasHoy = Venta::whereDate('created_at', $hoy)->count();
        $ventasMes = Venta::where('created_at', '>=', $inicioMes)->sum('total');
        $totalClientes = Cliente::count();
        
        // Usamos la misma lógica de tu modelo Producto (stock <= stock_minimo)
        $productosStockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->with('categoria')
            ->orderBy('stock', 'asc')
            ->get();

        // 2. Datos para el gráfico de Ventas de los últimos 7 días
        $fechas = collect();
        $totalesPorDia = collect();

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $fechas->push($fecha->format('d/m'));
            
            $sumaDia = Venta::whereDate('created_at', $fecha)->sum('total');
            $totalesPorDia->push((float) $sumaDia);
        }

        // 3. Top 5 Productos más vendidos
        $topProductos = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();

        $nombresTopProductos = $topProductos->map(fn($item) => $item->producto->nombre ?? 'Eliminado');
        $cantidadesTopProductos = $topProductos->map(fn($item) => (int) $item->total_vendido);

        // 4. Últimas 5 ventas realizadas
        $ultimasVentas = Venta::with('cliente')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'ventasHoy',
            'cantidadVentasHoy',
            'ventasMes',
            'totalClientes',
            'productosStockBajo',
            'fechas',
            'totalesPorDia',
            'nombresTopProductos',
            'cantidadesTopProductos',
            'ultimasVentas'
        ));
    }
}
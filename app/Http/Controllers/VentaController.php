<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('ventas.index', compact('clientes'));
    }

    public function buscar(Request $request)
    {
        return Producto::where('stock', '>', 0)
            ->where('nombre', 'like', '%' . $request->q . '%')
            ->orderBy('nombre')
            ->limit(10)
            ->get(['id', 'nombre', 'precio', 'stock']);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($datos) {
            $venta = Venta::create(['cliente_id' => $datos['cliente_id'] ?? null]);
            $total = 0;

            foreach ($datos['items'] as $item) {
                $producto = Producto::lockForUpdate()->find($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente de {$producto->nombre}.",
                    ]);
                }

                $subtotal = $producto->precio * $item['cantidad'];

                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ]);

                $producto->decrement('stock', $item['cantidad']);
                $total += $subtotal;
            }

            $venta->update(['total' => $total]);
        });

        return response()->json(['ok' => true]);
    }
}
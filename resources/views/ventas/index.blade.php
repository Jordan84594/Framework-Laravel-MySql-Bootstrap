@extends('layouts.app')

@section('content')
<h2 class="mb-3">Nueva venta</h2>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <input type="text" id="buscador" class="form-control mb-3" placeholder="Buscar producto..." autocomplete="off">
                <div id="resultados" class="list-group"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <select id="cliente" class="form-select mb-3">
                    <option value="">Consumidor final</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->apellido }}</option>
                    @endforeach
                </select>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th style="width: 100px">Cant.</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="carrito"></tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Total: $<span id="total">0.00</span></h4>
                    <button id="finalizar" class="btn btn-success">Finalizar venta</button>
                </div>

                <div id="mensaje" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const $ = id => document.getElementById(id);
const esc = t => String(t).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

const lineas = [];      // el carrito
let encontrados = [];   // últimos resultados de búsqueda
let temporizador;

// 1. Buscador: espera 300 ms después de la última tecla
$('buscador').addEventListener('input', () => {
    clearTimeout(temporizador);
    temporizador = setTimeout(buscar, 300);
});

async function buscar() {
    const q = $('buscador').value.trim();
    const res = await fetch(`{{ route('ventas.buscar') }}?q=${encodeURIComponent(q)}`);
    encontrados = await res.json();

    $('resultados').innerHTML = encontrados.map(p => `
        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between" data-id="${p.id}">
            <span>${esc(p.nombre)}</span>
            <span>$${Number(p.precio).toFixed(2)} · <small class="text-muted">${p.stock} unid.</small></span>
        </button>`).join('') || '<div class="text-muted">Sin resultados.</div>';
}

// 2. Click en un resultado: agregar al carrito
$('resultados').addEventListener('click', e => {
    const boton = e.target.closest('[data-id]');
    if (!boton) return;

    const p = encontrados.find(x => x.id == boton.dataset.id);
    const existente = lineas.find(l => l.id === p.id);

    if (existente) {
        if (existente.cantidad < p.stock) existente.cantidad++;
    } else {
        lineas.push({ id: p.id, nombre: p.nombre, precio: Number(p.precio), stock: p.stock, cantidad: 1 });
    }
    dibujar();
});

// 3. Dibujar el carrito y calcular el total
function dibujar() {
    $('carrito').innerHTML = lineas.map((l, i) => `
        <tr>
            <td>${esc(l.nombre)}<br><small class="text-muted">$${l.precio.toFixed(2)}</small></td>
            <td><input type="number" min="1" max="${l.stock}" value="${l.cantidad}" class="form-control form-control-sm" data-idx="${i}"></td>
            <td>$${(l.precio * l.cantidad).toFixed(2)}</td>
            <td><button type="button" class="btn btn-sm btn-danger" data-quitar="${i}">×</button></td>
        </tr>`).join('');

    const total = lineas.reduce((suma, l) => suma + l.precio * l.cantidad, 0);
    $('total').textContent = total.toFixed(2);
}

// 4. Cambiar cantidad
$('carrito').addEventListener('change', e => {
    const linea = lineas[e.target.dataset.idx];
    if (!linea) return;
    linea.cantidad = Math.min(Math.max(parseInt(e.target.value) || 1, 1), linea.stock);
    dibujar();
});

// 5. Quitar una línea
$('carrito').addEventListener('click', e => {
    const boton = e.target.closest('[data-quitar]');
    if (!boton) return;
    lineas.splice(boton.dataset.quitar, 1);
    dibujar();
});

// 6. Finalizar: enviar al servidor
$('finalizar').addEventListener('click', async () => {
    if (!lineas.length) return mostrar('Agrega al menos un producto.', 'warning');

    const res = await fetch('{{ route('ventas.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            cliente_id: $('cliente').value || null,
            items: lineas.map(l => ({ producto_id: l.id, cantidad: l.cantidad })),
        }),
    });
    const data = await res.json();

    if (res.ok) {
        lineas.length = 0;
        dibujar();
        buscar();
        mostrar('Venta registrada correctamente.', 'success');
    } else {
        mostrar(data.message || 'No se pudo registrar la venta.', 'danger');
    }
});

function mostrar(texto, tipo) {
    $('mensaje').innerHTML = `<div class="alert alert-${tipo} mb-0">${esc(texto)}</div>`;
}

buscar(); // al abrir, mostrar los primeros productos
</script>
@endpush
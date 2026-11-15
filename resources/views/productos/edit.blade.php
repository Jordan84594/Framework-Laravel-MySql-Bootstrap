@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">Editar Producto</div>
            <div class="card-body">
                <form action="{{ route('productos.update', $producto) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="form-control @error('precio') is-invalid @enderror">
                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $producto->stock) }}" class="form-control @error('stock') is-invalid @enderror">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
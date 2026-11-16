@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Registrar Producto</div>
            <div class="card-body">
                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
                            <option value="">-- Sin categoría --</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" class="form-control @error('precio') is-invalid @enderror">
                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock inicial</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control @error('stock') is-invalid @enderror">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock mínimo (alerta)</label>
                            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" class="form-control @error('stock_minimo') is-invalid @enderror">
                            @error('stock_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 650px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Editar Cliente</h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $cliente->apellido) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
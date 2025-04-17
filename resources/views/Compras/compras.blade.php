@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Listado de Compras</h4>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaCompra">
                        <i class="fas fa-plus"></i> Nueva Compra
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Proveedor</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($compras))
                                    @foreach ($compras as $compra)
                                        <tr>
                                            <td>{{ $compra['idCompra'] }}</td>
                                            <td>{{ $compra['nombreProveedor'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($compra['fechaCompra'])->format('Y-m-d') }}</td>
                                            <td>{{ $compra['totalCompra'] }}</td>
                                            <td>
                                                <!-- Botón Editar -->
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editarCompraModal{{ $compra['idCompra'] }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                 <!-- Botón Eliminar -->
                                                <form method="POST" action="{{ route('compras.destroy', $compra['idCompra']) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta compra?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No se pudieron cargar las compras.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Compra -->
<div class="modal fade" id="modalNuevaCompra" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('compras.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <select class="form-control" name="idProveedor" required>
                            <option value="">Seleccione</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor['idProveedor'] }}">{{ $proveedor['nombreProveedor'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaCompra">Fecha</label>
                        <input type="date" class="form-control" name="fechaCompra" required>
                    </div>
                    <div class="form-group">
                        <label>Productos</label>
                        <div id="productosContainer">
                            <div class="form-row mb-2 producto-row">
                                <div class="col">
                                    <select class="form-control" name="productos[0][idProducto]" required>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto['idProducto'] }}">{{ $producto['nombreProducto'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[0][precioCompra]" class="form-control" placeholder="Precio" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

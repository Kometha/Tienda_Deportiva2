@extends('layouts.app')

@section('content')
<?php
$mensajeExito = null;

// Verifica si se solicitó eliminar
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $urlEliminar = "http://localhost:3000/compras/EliminarCompra/$idEliminar";

    $responseEliminar = file_get_contents($urlEliminar);

    if (strpos($responseEliminar, "eliminada correctamente") === false) {
        die("❌ Error al eliminar la compra. Respuesta: " . $responseEliminar);
    } else {
        $mensajeExito = "✅ Compra eliminada correctamente.";
    }
}

// Obtener ventas actualizadas
$url = "http://localhost:3000/compras/MostrarCompra/0/detalles";
$response = file_get_contents($url);
$ventasRaw = json_decode($response, true);

// Validar si el JSON se decodificó correctamente
if ($ventasRaw === null) {
    die('Error al decodificar JSON: ' . json_last_error_msg());
}

$comprasAgrupadas = [];

foreach ($ventasRaw as $venta) {
    $id = $venta['idCompra'];

    if (!isset($comprasAgrupadas[$id])) {
        $comprasAgrupadas[$id] = [
            'idCompra' => $id,
            'nombreProveedor' => $venta['nombreProveedor'] ?? '',
            'apellidoCliente' => $venta['apellidoCliente'] ?? '',
            'fechaCompra' => $venta['fechaCompra'] ?? '',
            'totalCompra' => floatval($venta['totalCompra'] ?? 0)
        ];
    }
}

$compras = array_values($comprasAgrupadas);
?>
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

                    @if(isset($mensajeExito))
                    <div class="alert alert-success">{{ $mensajeExito }}</div>
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
                                        <button type="button" class="btn btn-sm btn-warning btn-editar-compra"
                                            data-id="{{ $compra['idCompra'] }}" data-toggle="modal"
                                            data-target="#modalEditarCompra" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="GET" style="display:inline;">
                                            <input type="hidden" name="eliminar" value="<?= $compra['idCompra'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Seguro que deseas eliminar esta compra?')">
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

<!--  Modal Nueva Compra -->
<div class="modal fade" id="modalNuevaCompra" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
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
                                        <option value="{{ $producto['idProducto'] }}">{{ $producto['nombreProducto'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[0][cantidad]" class="form-control"
                                        placeholder="Cantidad" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[0][precioCompra]" class="form-control"
                                        placeholder="Precio" required>
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

<!-- Modal Editar Compra -->
<div class="modal fade" id="modalEditarCompra" tabindex="-1" role="dialog" aria-labelledby="modalEditarCompraLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditarCompra" method="POST">
                @csrf
                <input type="hidden" id="edit-idCompra" name="idCompra">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarCompraLabel">Editar Compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-idProveedor">Proveedor</label>
                        <select class="form-control" id="edit-idProveedor" name="idProveedor" required>
                            <option value="">Seleccione</option>
                            @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor['idProveedor'] }}">{{ $proveedor['nombreProveedor'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-fechaCompra">Fecha</label>
                        <input type="date" class="form-control" id="edit-fechaCompra" name="fechaCompra" required>
                    </div>

                    <div class="form-group">
                        <label>Productos</label>
                        <div id="edit-productosContainer"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-editar-compra').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;

            fetch(`http://localhost:3000/compras/MostrarCompraById/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (!data || data.length === 0) return;

                    const compra = data[0];
                    document.getElementById('edit-idCompra').value = compra.idCompra;
                    document.getElementById('edit-idProveedor').value = compra.idProveedor;
                    document.getElementById('edit-fechaCompra').value = compra.fechaCompra
                        .slice(0, 10);

                    const container = document.getElementById('edit-productosContainer');
                    container.innerHTML = '';

                    data.forEach((item, index) => {
                        const row = document.createElement('div');
                        row.classList.add('form-row', 'mb-2');

                        row.innerHTML = `
                                <div class="col">
                                    <select class="form-control" name="productos[${index}][idProducto]" required>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto['idProducto'] }}">{{ $producto['nombreProducto'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[${index}][cantidad]" class="form-control" value="${item.cantidad}" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="productos[${index}][precioCompra]" class="form-control" value="${item.precioCompra}" required>
                                </div>
                            `;

                        container.appendChild(row);

                        const selector = row.querySelector('select');
                        if (selector) selector.value = item.idProducto;
                    });
                })
                .catch(error => {
                    console.error('Error al obtener datos de la compra:', error);
                });
        });
    });
});
</script>

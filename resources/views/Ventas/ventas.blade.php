@extends('layouts.app')

@section('content')
<?php
// Verifica si se solicitó eliminar
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $urlEliminar = "http://localhost:3000/ventas/EliminarVenta/$idEliminar";

    $responseEliminar = file_get_contents($urlEliminar);

    if (strpos($responseEliminar, "eliminada correctamente") === false) {
        die("❌ Error al eliminar la venta. Respuesta: " . $responseEliminar);
    }
}

// Obtener ventas actualizadas
$url = "http://localhost:3000/ventas/MostrarVentas/0/ventas";
$response = file_get_contents($url);
$ventasRaw = json_decode($response, true);

// Validar si el JSON se decodificó correctamente
if ($ventasRaw === null) {
    die('Error al decodificar JSON: ' . json_last_error_msg());
}

$ventasAgrupadas = [];

foreach ($ventasRaw as $venta) {
    $id = $venta['idVenta'];

    if (!isset($ventasAgrupadas[$id])) {
        $ventasAgrupadas[$id] = [
            'idVenta' => $id,
            'nombreCliente' => $venta['nombreCliente'] ?? '',
            'apellidoCliente' => $venta['apellidoCliente'] ?? '',
            'fechaVenta' => $venta['fechaVenta'] ?? '',
            'totalVenta' => 0
        ];
    }

    // Usar subtotal real por producto para sumar totalVenta
    if (isset($venta['totalVenta'])) {
        $ventasAgrupadas[$id]['totalVenta'] += floatval($venta['totalVenta']);
    }
}
?>

<link rel="stylesheet" href="./styleTable.css">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Listado de Ventas</h4>
                    <button class="btn" data-toggle="modal" data-target="#modalNuevaVenta">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </button>
                </div>
                <div class="card-body">
                    <?php if (session('success')): ?>
                        <div class="alert alert-success"><?= session('success') ?></div>
                    <?php endif; ?>

                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                    <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($ventasAgrupadas)): ?>
            <?php foreach ($ventasAgrupadas as $venta): ?>
                <tr>
                    <td><?= $venta['idVenta'] ?></td>
                    <td><?= $venta['nombreCliente'] . ' ' . $venta['apellidoCliente'] ?></td>
                    <td><?= date('Y-m-d', strtotime($venta['fechaVenta'])) ?></td>
                    <td>L. <?= number_format($venta['totalVenta'], 2) ?></td>
                    <td>
                    <form method="GET" style="display:inline;">
    <input type="hidden" name="eliminar" value="<?= $venta['idVenta'] ?>">
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta venta?')">
        <i class="fas fa-trash"></i>
    </button>
</form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No se pudieron cargar las ventas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Venta -->
<div class="modal fade" id="modalNuevaVenta" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Venta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <select class="form-control" name="idCliente" required>
                            <option value="">Seleccione</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente['idCliente'] }}">{{ $cliente['nombreCliente'] }} {{ $cliente['apellidoCliente'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaVenta">Fecha</label>
                        <input type="date" class="form-control" name="fechaVenta" required>
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


<script>
function eliminarVenta(id) {
    // Llamar al endpoint de eliminar
    fetch(`http://localhost:3000/ventas/EliminarVenta/${id}`)
    .then(response => response.json())
    .then(data => {
        // Volver a cargar la página para refrescar la tabla
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}
</script>

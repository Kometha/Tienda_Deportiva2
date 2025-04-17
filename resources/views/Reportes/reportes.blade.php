@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Generar Reportes</h3>
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#crearReporteModal">
                        <i class="fas fa-plus"></i> Generar Reporte
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tipo de Reporte</th>
                                    <th>Formato</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Compartido</th>
                                    <th>Generado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($reportes) && is_array($reportes))
                                    @foreach($reportes as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['tipoReporte'] ?? '' }}</td>
                                            <td>{{ $item['formato'] ?? '' }}</td>
                                            <td>{{ isset($item['fechaInicio']) ? \Carbon\Carbon::parse($item['fechaInicio'])->format('d/m/Y') : '' }}</td>
                                            <td>{{ isset($item['fechaFin']) ? \Carbon\Carbon::parse($item['fechaFin'])->format('d/m/Y') : '' }}</td>
                                            <td>{{ $item['destinatario'] ?? '' }}</td>
                                            <td>{{ isset($item['fechaGeneracion']) ? \Carbon\Carbon::parse($item['fechaGeneracion'])->locale('es')->isoFormat('D [de] MMMM [del] YYYY') : 'Nunca' }}</td>
                                            <td>

                                            <a href="{{ route('reportes.show', $item['idReporteGenerado'] ?? '') }}?idTipoReporte={{ $item['idTipoReporte'] }}&formato={{ $item['formato'] }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>


                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editarReporteModal{{ $item['idReporteGenerado'] ?? '' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('reportes.destroy', $item['idReporteGenerado'] ?? '') }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este reporte?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>

                                        <!-- Modal para editar -->
                                        <div class="modal fade" id="editarReporteModal{{ $item['idReporteGenerado'] ?? '' }}" tabindex="-1" role="dialog" aria-labelledby="editarReporteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editarReporteModalLabel">Editar Reporte</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('reportes.update', $item['idReporteGenerado'] ?? '') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="tipoReporte">Tipo de Reporte</label>
                                                                <select class="form-control" id="tipoReporte" name="idTipoReporte" required>
                                                                    <option value="1" {{ (isset($item['idTipoReporte']) && $item['idTipoReporte'] == 1) ? 'selected' : '' }}>Reporte de Ventas</option>
                                                                    <option value="2" {{ (isset($item['idTipoReporte']) && $item['idTipoReporte'] == 2) ? 'selected' : '' }}>Clientes activos</option>
                                                                    <option value="3" {{ (isset($item['idTipoReporte']) && $item['idTipoReporte'] == 3) ? 'selected' : '' }}>Clientes inactivos</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="formato">Formato</label>
                                                                <select class="form-control" id="formato" name="formato" required>
                                                                    <option value="PDF" {{ (isset($item['formato']) && $item['formato'] == 'PDF') ? 'selected' : '' }}>PDF</option>
                                                                    <option value="EXCEL" {{ (isset($item['formato']) && $item['formato'] == 'EXCEL') ? 'selected' : '' }}>Excel</option>
                                                                    <option value="HTML" {{ (isset($item['formato']) && $item['formato'] == 'HTML') ? 'selected' : '' }}>HTML</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="fechaInicio">Fecha Inicio</label>
                                                                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" 
                                                                    value="{{ isset($item['fechaInicio']) ? \Carbon\Carbon::parse($item['fechaInicio'])->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="fechaFin">Fecha Fin</label>
                                                                <input type="date" class="form-control" id="fechaFin" name="fechaFin" 
                                                                    value="{{ isset($item['fechaFin']) ? \Carbon\Carbon::parse($item['fechaFin'])->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No hay reportes disponibles</td>
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

<!-- Modal para crear nuevo reporte -->
<div class="modal fade" id="crearReporteModal" tabindex="-1" role="dialog" aria-labelledby="crearReporteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearReporteModalLabel">Nuevo Reporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reportes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="idTipoReporte">Tipo de Reporte</label>
                        <select class="form-control" id="idTipoReporte" name="idTipoReporte" required>
                            <option value="">Seleccione</option>
                            <option value="1">Reporte de Ventas</option>
                            <option value="2">Clientes activos</option>
                            <option value="3">Clientes inactivos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formato">Formato</label>
                        <select class="form-control" id="formato" name="formato" required>
                            <option value="">Seleccione</option>
                            <option value="PDF">PDF</option>
                            <option value="EXCEL">Excel</option>
                            <option value="HTML">HTML</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFin">Fecha Fin</label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($reporte) && isset($idTipoReporte))
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>
                @if(isset($idTipoReporte))
                    @switch($idTipoReporte)
                        @case(1) Reporte de Ventas @break
                        @case(2) Clientes Activos @break
                        @case(3) Clientes Inactivos @break
                    @endswitch
                @else
                    Tipo de Reporte No Especificado
                @endif
            </h4>
            
        </div>
        <div class="card-body">
            @if(empty($reporte))
                <div class="alert alert-warning">
                    No se encontraron datos para este reporte.
                </div>
            @else
                <div class="table-responsive">
                    @if(isset($idTipoReporte))
                        @switch($idTipoReporte)
                            @case(1) {{-- Reporte de Ventas --}}
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Mes</th>
                                            <th>Total de Ventas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ isset($data['mes']) ? \Carbon\Carbon::createFromFormat('Y-m', $data['mes'])->format('F Y') : 'N/A' }}</td>
                                                <td>L. {{ isset($data['totalVentas']) ? number_format($data['totalVentas'], 2) : '0.00' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @break
                            
                            @case(2) {{-- Clientes Activos --}}
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>DNI</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Última Compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ($data['nombre'] ?? '') }} {{ ($data['apellido'] ?? '') }}</td>
                                                <td>{{ $data['dni'] ?? '' }}</td>
                                                <td>{{ $data['email'] ?? '' }}</td>
                                                <td>{{ $data['telefono'] ?? '' }}</td>
                                                <td>{{ isset($data['ultimaCompra']) ? \Carbon\Carbon::parse($data['ultimaCompra'])->format('d/m/Y H:i') : 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @break
                            
                            @case(3) {{-- Clientes Inactivos --}}
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>DNI</th>
                                            <th>Sexo</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Última Compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ($data['nombre'] ?? '') }} {{ ($data['apellido'] ?? '') }}</td>
                                                <td>{{ $data['dni'] ?? '' }}</td>
                                                <td>{{ $data['sexo'] ?? '' }}</td>
                                                <td>{{ $data['email'] ?? '' }}</td>
                                                <td>{{ $data['telefono'] ?? '' }}</td>
                                                <td>{{ $data['direccion'] ?? '' }}</td>
                                                <td>{{ isset($data['ultimaCompra']) ? \Carbon\Carbon::parse($data['ultimaCompra'])->format('d/m/Y H:i') : 'Nunca' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @break
                            
                            @default
                                <div class="alert alert-info">
                                    Tipo de reporte no reconocido (ID: {{ $reporte['idTipoReporte'] }}). Datos recibidos:
                                    <pre>{{ json_encode($datosReporte, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                        @endswitch
                    @else
                        <div class="alert alert-danger">
                            El tipo de reporte no está definido. Datos recibidos:
                            <pre>{{ json_encode($reporte, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            @endif
            


            <a href="{{ route('Reportes.download') }}?reporte={{ urlencode(json_encode($reporte)) }}&formato={{ urlencode($formato) }}" class="btn btn-success">
                <i class="fas fa-cloud-download-alt"></i> Descargar 
            </a>


            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Formatear fechas al mostrar el modal de edición
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('input[type="date"]').each(function() {
                if(this.value) {
                    this.value = this.value.split('T')[0];
                }
            });
        });
    });
</script>
@endsection







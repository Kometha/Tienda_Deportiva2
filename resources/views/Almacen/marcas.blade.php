@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Gestión de Marcas</h3>
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#crearModal">
                        <i class="fas fa-plus"></i> Agregar Marca
                    </button>
                </div>

                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Nombre de Marca</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($marcas))
                                @php
                                    $total = count($marcas);
                                @endphp
                                @foreach (collect($marcas)->reverse() as $marca)
                                    <tr>
                                        <td>{{ $total - $loop->index }}</td>
                                        <td>{{ $marca['nombreMarca'] }}</td>
                                        <td>
                                            <!-- Botón Editar -->
                                            <a href="/marcas?editar_id={{ $marca['idMarca'] }}&editar_nombre={{ urlencode($marca['nombreMarca']) }}"
                                               class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Botón Eliminar con confirmación -->
                                            <form method="POST" action="/marcas" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="idMarca" value="{{ $marca['idMarca'] }}">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta marca?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">No se pudieron cargar las marcas.</td>
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

<!-- Modal Crear -->
<div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="/marcas">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearModalLabel">Nueva Marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="nombreMarca" class="form-control" placeholder="Nombre de la marca" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
@if(request('editar_id'))
<div class="modal fade show" id="editarModal" tabindex="-1" role="dialog" style="display:block;" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="/marcas">
                @csrf
                @method('PUT')
                <input type="hidden" name="idMarca" value="{{ request('editar_id') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Marca</h5>
                    <a href="/marcas" class="close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="nombreMarca" class="form-control"
                               value="{{ request('editar_nombre') }}" placeholder="Nombre de la marca" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/marcas" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-sm btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editarModal').modal('show');
    });
</script>
@endif

@endsection

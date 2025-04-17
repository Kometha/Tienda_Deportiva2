@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Administración de Proveedores</h3>
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#crearModal">
                        <i class="fas fa-plus"></i> Agregar Proveedor
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
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($proveedores))
                                @php
                                    $total = count($proveedores);
                                @endphp
                                @foreach (array_reverse($proveedores) as $proveedor)
                                    <tr>
                                        <td>{{ $total - $loop->index }}</td>
                                        <td>{{ $proveedor['nombreProveedor'] }}</td>
                                        <td>{{ $proveedor['email'] }}</td>
                                        <td>{{ $proveedor['telefono'] }}</td>
                                        <td>{{ $proveedor['direccion'] }}</td>
                                        <td>
                                          <!-- Botón Editar -->
                                          <a href="/proveedores?editar_id={{ $proveedor['idProveedor'] }}
                                              &editar_nombre={{ urlencode($proveedor['nombreProveedor']) }}
                                              &editar_email={{ urlencode($proveedor['email']) }}
                                              &editar_telefono={{ urlencode($proveedor['telefono']) }}
                                              &editar_direccion={{ urlencode($proveedor['direccion']) }}"
                                            class="btn btn-sm btn-info me-1">
                                              <i class="fas fa-edit"></i>
                                          </a>

                                          <!-- Botón Eliminar con confirmación -->
                                          <form method="POST" action="/proveedores" style="display:inline;">
                                              @csrf
                                              @method('DELETE')
                                              <input type="hidden" name="idProveedor" value="{{ $proveedor['idProveedor'] }}">
                                              <button type="submit" class="btn btn-sm btn-danger"
                                                      onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                                  <i class="fas fa-trash"></i>
                                              </button>
                                          </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No se pudieron cargar los proveedores.</td>
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
            <form method="POST" action="/proveedores">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearModalLabel">Nuevo Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreProveedor">Nombre del Proveedor</label>
                        <input type="text" name="nombreProveedor" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Dirección" required>
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
            <form method="POST" action="/proveedores">
                @csrf
                @method('PUT')
                <input type="hidden" name="idProveedor" value="{{ request('editar_id') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Proveedor</h5>
                    <a href="/proveedores" class="close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editNombreProveedor">Nombre del Proveedor</label>
                        <input type="text" name="nombreProveedor" class="form-control"
                               value="{{ request('editar_nombre') }}" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ request('editar_email') }}" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="editTelefono">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="{{ request('editar_telefono') }}" placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="editDireccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control"
                               value="{{ request('editar_direccion') }}" placeholder="Dirección" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/proveedores" class="btn btn-secondary">Cancelar</a>
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

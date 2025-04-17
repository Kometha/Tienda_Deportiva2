@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Lista de Productos</h3>
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#crearProductoModal">
                        <i class="fas fa-plus-circle mr-2"></i> Agregar Producto
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any()))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ $errors->first() }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Proveedor</th>
                                    <th>Categoría</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Ordenar el array por ID descendente
                                    $productosOrdenados = collect($productos)->sortByDesc('idProducto')->all();
                                    $total = count($productosOrdenados);
                                @endphp
                                @foreach($productosOrdenados as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['nombreProducto'] }}</td>
                                        <td>{{ $item['descripcion'] }}</td>
                                        <td>{{ $item['proveedor'] }}</td>
                                        <td>{{ $item['categoria'] }}</td>
                                        <td>${{ number_format($item['precioCompra'], 2) }}</td>
                                        <td>${{ number_format($item['precioVenta'], 2) }}</td>
                                        <td>
                                            <span class="badge {{ $item['stock'] <= $item['bajoStock'] ? 'badge-danger' : 'badge-success' }}">
                                                {{ $item['stock'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editarProductoModal{{ $item['idProducto'] }}" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('productos.destroy', $item['idProducto']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="editarProductoModal{{ $item['idProducto'] }}" tabindex="-1" role="dialog" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-gradient-primary text-white">
                                                    <h5 class="modal-title" id="editarProductoModalLabel">
                                                        <i class="fas fa-edit mr-2"></i>Editar Producto
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('productos.update', $item['idProducto']) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body py-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nombreProducto" class="font-weight-bold">Nombre del Producto <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" value="{{ $item['nombreProducto'] }}" required>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="descripcion" class="font-weight-bold">Descripción</label>
                                                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2">{{ $item['descripcion'] }}</textarea>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="proveedor" class="font-weight-bold">Proveedor <span class="text-danger">*</span></label>
                                                                    <select class="form-control select2" id="proveedor" name="idProveedor" required>
                                                                        @foreach($proveedores as $proveedor)
                                                                            <option value="{{ $proveedor['id'] }}" {{ $proveedor['nombre'] == $item['proveedor'] ? 'selected' : '' }}>{{ $proveedor['nombre'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="categoria" class="font-weight-bold">Categoría <span class="text-danger">*</span></label>
                                                                    <select class="form-control select2" id="categoria" name="idCategoria" required>
                                                                        @foreach($categorias as $categoria)
                                                                            <option value="{{ $categoria['id'] }}" {{ $categoria['nombre'] == $item['categoria'] ? 'selected' : '' }}>{{ $categoria['nombre'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="marca" class="font-weight-bold">Marca</label>
                                                                    <select class="form-control select2" id="marca" name="idMarca">
                                                                        @foreach($marcas as $marca)
                                                                            <option value="{{ $marca['id'] }}">{{ $marca['nombre'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="precioCompra" class="font-weight-bold">Precio de Compra <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">$</span>
                                                                        </div>
                                                                        <input type="number" step="0.01" class="form-control" id="precioCompra" name="precioCompra" value="{{ $item['precioCompra'] }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mt-2">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="precioVenta" class="font-weight-bold">Precio de Venta <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">$</span>
                                                                        </div>
                                                                        <input type="number" step="0.01" class="form-control" id="precioVenta" name="precioVenta" value="{{ $item['precioVenta'] }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="stock" class="font-weight-bold">Stock <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ $item['stock'] }}" required>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="bajoStock" class="font-weight-bold">Stock Mínimo</label>
                                                                    <input type="number" class="form-control" id="bajoStock" name="bajoStock" value="{{ $item['bajoStock'] }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="imagen" class="font-weight-bold">Imagen del Producto</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" id="imagen" name="imagen" accept="image/*">
                                                                        <label class="custom-file-label" for="imagen">{{ $item['imagen'] ?? 'Seleccionar archivo...' }}</label>
                                                                    </div>
                                                                    <input type="hidden" name="imagen_actual" value="{{ $item['imagen'] ?? '/default.jpg' }}">
                                                                    <small class="form-text text-muted">Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times mr-2"></i>Cancelar
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar producto - Versión mejorada -->
<div class="modal fade" id="crearProductoModal" tabindex="-1" role="dialog" aria-labelledby="crearProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="crearProductoModalLabel">
                    <i class="fas fa-cube mr-2"></i>Nuevo Producto
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCrearProducto" method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombreProducto" class="font-weight-bold">Nombre del Producto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" placeholder="Ej: Camiseta deportiva" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion" class="font-weight-bold">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Características del producto"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="proveedor" class="font-weight-bold">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="proveedor" name="idProveedor" required>
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor['id'] }}">{{ $proveedor['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria" class="font-weight-bold">Categoría <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="categoria" name="idCategoria" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria['id'] }}">{{ $categoria['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="marca" class="font-weight-bold">Marca</label>
                                <select class="form-control select2" id="marca" name="idMarca">
                                    <option value="">Seleccione una marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca['id'] }}">{{ $marca['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="precioCompra" class="font-weight-bold">Precio de Compra <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="precioCompra" name="precioCompra" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precioVenta" class="font-weight-bold">Precio de Venta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="precioVenta" name="precioVenta" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock" class="font-weight-bold">Stock Inicial <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="0" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bajoStock" class="font-weight-bold">Stock Mínimo</label>
                                <input type="number" class="form-control" id="bajoStock" name="bajoStock" placeholder="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="imagen" class="font-weight-bold">Imagen del Producto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imagen" name="imagen" accept="image/*">
                                    <label class="custom-file-label" for="imagen">Seleccionar archivo...</label>
                                </div>
                                <small class="form-text text-muted">Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
    }
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    .modal-content {
        border-radius: 0.5rem;
    }
    .custom-file-label::after {
        content: "Buscar";
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar select2
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('#crearProductoModal')
        });

        // Mostrar nombre de archivo en input file
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Validar que precio venta sea mayor que precio compra
        $('#precioVenta').on('change', function() {
            let precioCompra = parseFloat($('#precioCompra').val());
            let precioVenta = parseFloat($(this).val());
            
            if (precioVenta <= precioCompra) {
                alert('El precio de venta debe ser mayor que el precio de compra');
                $(this).val('');
            }
        });
    });
</script>
@endpush

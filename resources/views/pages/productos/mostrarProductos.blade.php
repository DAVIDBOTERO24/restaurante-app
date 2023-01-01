@extends('adminlte::page')

@section('title', '| Productos')

@section('content_header')
@stop

@section('content')
    <section class="content-header mt-n2">
        <div id="formEditarProducto" style="display: none">
            <form id="formularioProducto" action="" method="post">
                @csrf
                @method('put')
                <div class="card card-orange mx-n3">
                    <div class="card-header">
                        <h3 class="card-title">Consultar producto</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                            <button type="button" id="btnOcultar" class="btn btn-tool"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="idProducto" name="id" value="{{ old('id') }}">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nombreProducto">Nombre</label>
                                    <input type="text" id="nombreProducto"
                                        class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                        autocomplete="off" placeholder="Nombre">
                                    @error('nombre')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="codigoProducto">Código</label>
                                    <input type="text" id="codigoProducto"
                                        class="form-control @error('codigo') is-invalid @enderror" name="codigo"
                                        autocomplete="off" placeholder="Código">
                                    @error('codigo')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="proveedorProducto">Proveedor</label>
                                    <select id="proveedorProducto" class="form-control" name="id_proveedor">
                                        <option value="" disabled selected>Seleccione el proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id_proveedores }}">{{ $proveedor->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="unidadProducto">Unidad de medida</label>
                                    <select id="unidadProducto" class="form-control" name="id_unidad">
                                        <option value="" disabled selected>Seleccione la unidad</option>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad->id_unidades }}">{{ $unidad->unidad }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="totalProducto">Total unidades</label>
                                    <input type="number" id="totalProducto" class="form-control" name="total"
                                        autocomplete="off" placeholder="Total inicial">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                        <button type="button" id="eliminar_producto2" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>


        <div class="card card-orange mt-n1 mx-n3">
            <div class="card-header">
                <h3 class="card-title">Listado de productos</h3>
            </div>
            <div class="card-body">
                <table id="tabla_productos" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Unidad</th>
                            <th>Proveedor</th>
                            <th>Total en exitencia</th>
                            <th>Ingresado por</th>
                            <th>Ingresado por</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@stop

@section('css')
    <!-- Token de Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)

@section('js')
    <script src="{{ asset('js/productos/productosMostrar.js') }}"></script>

    @if (session('producto_actualizado'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'El producto <b>{{ session('producto_actualizado') }}</b> se ha actualizado exitosamente',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif
@stop

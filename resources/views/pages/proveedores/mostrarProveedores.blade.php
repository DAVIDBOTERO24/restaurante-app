@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
@stop

@section('content')
    <section class="content-header mt-n2">
        <form action="" method="post">
            @csrf
            {{method_field('delete')}}
            <div id="tarjetaProveedores" class="card card-dark mx-n3">
                <div class="card-header">
                    <h3 class="card-title">Consultar proveedor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" ><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nombreProveedor">Ingrese el nombre del proveedor</label>
                                <input type="text" id="nombreProveedor" class="form-control" name="nombre" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nitProveedor">Ingrese el Nit del proveedor</label>
                                <input type="text" id="nitProveedor" class="form-control" name="nit" placeholder="Nit">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="telefonoProveedor">Ingrese el teléfono del proveedor</label>
                                <input type="tel" id="telefonoProveedor" class="form-control" name="telefono" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="correoProveedor">Ingrese el correo electrónico del proveedor</label>
                                <input type="tel" id="correoProveedor" class="form-control" name="correo" placeholder="Correo electrónico">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="direccionProveedor">Dirección del proveedor</label>
                                <input type="tel" id="direccionProveedor" class="form-control" name="direccion" placeholder="Dirección">
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success">Actualizar</button>
                    
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Quieres borrar?')">Eliminar</button>
                </div>
            </div>
        </form>

        <div class="card card-dark mt-n1 mx-n3">
            <div class="card-header">
                <h3 class="card-title">Listado de proveedores</h3>
            </div>
            <div class="card-body">
                <table id="tabla_proveedores" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Nit</th>
                            <th>Teléfono</th>
                            <th>Correo electrónico</th>
                            <th>Dirección</th>
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
@stop

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('js')
    <script src="{{ asset('js/proveedores/proveedoresMostrar.js') }}"></script>
@stop

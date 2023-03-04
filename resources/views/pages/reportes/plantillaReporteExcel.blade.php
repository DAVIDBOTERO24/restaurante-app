@inject('carbon', 'Carbon\Carbon')

<table>
    <thead>
        <tr>
            <th><strong>ID</strong></th>
            <th><strong>Estado</strong></th>
            <th><strong>Proveedor</strong></th>
            <th><strong>Producto</strong></th>
            <th><strong>Código</strong></th>
            <th><strong>Cantidad</strong></th>
            <th><strong>Última existencia</strong></th>
            <th><strong>Costo</strong></th>
            <th><strong>Costo unitario</strong></th>
            <th><strong>Fecha vencimiento</strong></th>
            <th><strong>Fecha registro</strong></th>
            <th><strong>Hora registro</strong></th>
            <th><strong>Ingresado por</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>{{ $registro->id_inventario }}</td>
                @if( $registro->estado)
                    <td>Ingreso</td>
                @else
                    <td>Salida</td>
                @endif
                <td>{{ $registro->proveedor }}</td>
                <td>{{ $registro->producto }} {{ $registro->peso }} {{ $registro->abreviacion }}</td>
                <td>{{ $registro->codigo }}</td>
                <td>{{ $registro->cantidad }}</td>
                <td>{{ $registro->cantidad_producto }}</td>
                @if($registro->costo)
                    <td>{{ $registro->costo }}</td>
                @else
                    <td>-</td>
                @endif
                @if($registro->costo_unitario)
                    <td>{{ $registro->costo_unitario }}</td>
                @else
                    <td>-</td>
                @endif
                @if($registro->fecha_vencimiento)
                    <td>{{ $registro->fecha_vencimiento }}</td>
                @else
                    <td>-</td>
                @endif
                <td>{{ $carbon::parse($registro->fecha)->format('d-m-Y') }}</td>
                <td>{{ $carbon::parse($registro->fecha)->format('h:i a')}}</td>
                <td>{{ $registro->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

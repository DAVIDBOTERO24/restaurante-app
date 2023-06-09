<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventarioController extends Controller
{
    protected $inventarios;
    protected $productos;

    public function __construct(Inventario $inventarios, Producto $productos)
    {
        $this->inventarios = $inventarios;
        $this->productos = $productos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.inventario.mostrarInventario');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = $this->productos->obtenerProductos();
        return view('pages.inventario.crearInventario', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'estado' => ['required'],
        ], [
            'estado.required' => 'Se requiere que ingrese el estado del registro',
        ]);

        if ($request['estado'] == 1) {
            $validaciones = [
                'id_producto' => ['required'],
                'cantidad' => ['required', 'numeric'],
                'costo' => ['required', 'numeric'],
                'fecha_vencimiento' => ['required', 'date_format:Y-m-d']
            ];
        } else {
            $validaciones = [
                'id_producto' => ['required'],
                'cantidad' => ['required', 'numeric'],
            ];
        }

        $request->validate($validaciones, [
            'id_producto.required' => 'Se requiere que ingrese el nombre del producto',
            'cantidad.required' => 'Se requiere que ingrese la cantidad del producto',
            'cantidad.numeric' => 'La cantidad debe ser un valor númerico entero',
            'costo.required' => 'Se requiere que ingrese el costo del producto',
            'costo.numeric' => 'El costo debe ser un valor númerico entero',
            'fecha_vencimiento.required' => 'Se requiere que ingrese la fecha de vencimiento del producto',
            'fecha_vencimiento.date_format' => 'La fecha de vencimiento debe tener un formato válido'
        ]);


        $producto = $this->productos->obtenerProducto($request['id_producto']);
        if ($request['estado'] == 1) {
            $producto->total += $request['cantidad'];
            $request['costo_unitario'] = $request['costo'] / $request['cantidad'];
        } else {
            $totalActual = $producto->total;
            $producto->total -= $request['cantidad'];
            if ($producto->total < 0) {
                return redirect()->route('crearInventario')->withInput()->with('inventario_negativo', [$producto->nombre, $totalActual, $request['cantidad']]);
            }
        }
        $producto->save();

        $request['id_usuario'] = auth()->user()->id_usuarios;
        $request['fecha'] = Carbon::now()->toDateTimeString();
        $request['cantidad_producto'] = $producto->total;

        $inventario = Inventario::create($request->all());
        $inventario->save();

        $info = [$inventario->estado, $inventario->cantidad, $producto->nombre, false];
        if ($producto->total <= 10) {
            $info[3] = true;
        }
        return redirect()->route('crearInventario')->with('inventario_creado', $info);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registroInventario = Inventario::find($id);
        $fechaInventario = Carbon::parse($registroInventario->fecha)->format('Y-m-d');
        $fechaActual = Carbon::now()->toDateString();

        if ($fechaInventario < $fechaActual) {
            $isDelete = false;
        } else {
            $producto = $this->productos->obtenerProducto($registroInventario->id_producto);
            if ($registroInventario->estado) {
                $producto->total -= $registroInventario->cantidad;
            } else {
                $producto->total += $registroInventario->cantidad;
            }
            $producto->save();
            Inventario::destroy($id);
            $isDelete = true;
        }
        return response()->json(['message' => $isDelete]);
    }

    /**
     * 
     */
    public function obtenerListaInventarios(Request $request)
    {
        if ($request->ajax()) {
            $listaInventarios = $this->inventarios->obtenerInformacionInventarios();
            return DataTables::of($listaInventarios)->make(true);
        }
    }

    /**
     * 
     */
    public function obtenerListaInventario(Request $request, $id)
    {
        if ($request->ajax()) {
            $listaInventario = $this->inventarios->obtenerInventario($id);
            return DataTables::of($listaInventario)->make(true);
        }
    }

    /**
     * 
     */
    public function obtenerListaPedidosProveedor(Request $request, $id)
    {
        if ($request->ajax()) {
            $listaregistros = $this->inventarios->obtenerPedidosProveedor($id);
            return DataTables::of($listaregistros)->make(true);
        }
    }
}

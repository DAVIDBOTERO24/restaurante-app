<?php

namespace App\Http\Controllers;

use App\Exports\ListadoProductos;
use App\Exports\ListadoProveedores;
use App\Exports\PedidosProveedor;
use App\Exports\RegistrosInventario;
use App\Exports\RegistrosProducto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inventario;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reportes.generarReporte');
    }

    /**
     * 
     */
    public function exportarReportes(Request $request)
    {
        // $this->validarFiltros($request);
        $datos = $request->all();

        if ($datos['formato'] == 'excel') {
            return $this->exportarReportesExcel($datos);
        } else if ($datos['formato'] == 'pdf') {
            return $this->exportarReportesPdf($datos);
        }
    }

    public function validarFiltros(Request $request)
    {
        $tipoReporte = $request->input('tipoReporte');
        $reglas = [
            'anio' => 'required|numeric',
            'mes' => 'required|numeric'
        ];

        if ($tipoReporte == 2) {
            $reglas['fecha'] = 'required|date_format:d/m/Y';
        } else if ($tipoReporte == 3) {
            $reglas['identificacion'] = 'required|exists:se_personas,identificacion';
        }

        $request->validate($reglas, [
            'anio.required' => 'Se requiere que elija un año',
            'anio.numeric' => 'El año debe ser un número',

            'mes.required' => 'Se requiere que eilija un mes',
            'mes.numeric' => 'El mes debe ser un número',

            'fecha.required' => 'Se requiere que ingrese la fecha',
            'fecha.date_format' => 'La fecha debe tener un formato valido',

            'identificacion.required' => 'Se requiere que ingrese el número de indentificación de la persona',
            'identificacion.exists' => 'La identificación ingresada no existe en el sistema'
        ]);
    }

    /**
     * 
     */
    public function exportarReportesExcel($datos)
    {
        $tipoReporte = $datos['tipoReporte'];

        if ($tipoReporte == 1) {
            [$consulta, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            return (new ListadoProveedores($consulta, $titulo))->download($titulo . '.xlsx');
        }
        if ($tipoReporte == 2) {
            [$consulta, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            return (new PedidosProveedor($consulta, $titulo))->download($titulo . '.xlsx');
        }
        if ($tipoReporte == 3) {
            [$consulta, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            return (new ListadoProductos($consulta, $titulo))->download($titulo . '.xlsx');
        }
        if ($tipoReporte == 4) {
            [$consulta, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            return (new RegistrosProducto($consulta, $titulo))->download($titulo . '.xlsx');
        }
        if ($tipoReporte == 5) {
            [$consulta, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            return (new RegistrosInventario($consulta, $titulo))->download($titulo . '.xlsx');
        }
    }

    /**
     * 
     */
    public function exportarReportesPdf($datos)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3600);

        $tipoReporte = $datos['tipoReporte'];

        if ($tipoReporte == 1) {
            [$registros, $titulo] = $this->consultaListadoProveedores($datos['anio'], $datos['mes'], $datos['estado']);
            $reportePdf = PDF::loadView('pages.reportes.listadoProveedoresPdf', compact('registros', 'titulo'));
        } else if ($tipoReporte == 2) {
            [$registros, $titulo] = $this->consultaPedidosProveedor($datos['anio'], $datos['mes'], $datos['estado']);
            $reportePdf = PDF::loadView('pages.reportes.pedidosProveedorPdf', compact('registros', 'titulo'));
        } else if ($tipoReporte == 3) {
            [$registros, $titulo] = $this->consultaListadoProductos($datos['anio'], $datos['mes'], $datos['estado']);
            $reportePdf = PDF::loadView('pages.reportes.listadoProductosPdf', compact('registros', 'titulo'));
        } else if ($tipoReporte == 4) {
            [$registros, $titulo] = $this->consultaRegistrosProducto($datos['anio'], $datos['mes'], $datos['estado']);
            $reportePdf = PDF::loadView('pages.reportes.registrosProductoPdf', compact('registros', 'titulo'));
        } else if ($tipoReporte == 5) {
            [$registros, $titulo] = $this->consultaRegistrosInventario($datos['anio'], $datos['mes'], $datos['estado']);
            $reportePdf = PDF::loadView('pages.reportes.registrosInventarioPdf', compact('registros', 'titulo'));
        }

        $reportePdf->set_paper('letter', 'landscape');
        return $reportePdf->download($titulo . '.pdf');
    }

    /**
     * 
     */
    public function consultaListadoProveedores()
    {
        try {
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
    }

    /**
     * 
     */
    public function consultaPedidosProveedor()
    {
        try {
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
    }

    /**
     * 
     */
    public function consultaListadoProductos()
    {
        try {
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
    }

    /**
     * 
     */
    public function consultaRegistrosProducto()
    {
        try {
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
    }

    /**
     * 
     */
    public function consultaRegistrosInventario($anio, $mes, $estado)
    {
        try {
            $inventarios = Inventario::select('inventario.*', 'pdt.codigo', 'pdt.nombre AS producto', 'pdt.peso', 'prov.nombre AS proveedor', 'user.name', 'uni.abreviacion')
                ->leftjoin('productos AS pdt', 'inventario.id_producto', '=', 'pdt.id_productos')
                ->leftjoin('unidades AS uni', 'pdt.id_unidad', '=', 'uni.id_unidades')
                ->leftjoin('proveedores AS prov', 'pdt.id_proveedor', '=', 'prov.id_proveedores')
                ->leftjoin('usuarios AS user', 'inventario.id_usuario', '=', 'user.id_usuarios')
                ->where('pdt.estado_activacion', true)->where('prov.estado_activacion', true)
                ->whereYear('fecha', $anio)->whereMonth('fecha', $mes)->orderBy('id_inventario');

            if ($estado == 1) {
                $consulta = $inventarios->where('estado', true)->get();
                $titulo = 'Ingresos de inventario ' . $mes . '-' . $anio;
            } else if ($estado == 2) {
                $consulta = $inventarios->where('estado', false)->get();
                $titulo = 'Salidas de inventario ' . $mes . '-' . $anio;
            } else if ($estado == 3) {
                $consulta = $inventarios->get();
                $titulo = 'Registros de inventario ' . $mes . '-' . $anio;
            }
            return [$consulta, $titulo];
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
    }
}

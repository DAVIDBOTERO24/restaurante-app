<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $primaryKey = 'id_inventario';

    protected $fillable = ['estado', 'cantidad', 'cantidad_producto', 'costo', 'costo_unitario', 'fecha_vencimiento', 'fecha', 'id_producto', 'id_usuario'];

    public function obtenerInventarios(){
        try {
            $inventarios = Inventario::all();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
        return $inventarios;
    }

    public function obtenerInventario($id){    
        try {
            $inventario = Inventario::select('inventario.*', 'user.name')
            ->leftjoin('productos AS pdt', 'inventario.id_producto', '=', 'pdt.id_productos')
            ->leftjoin('usuarios AS user', 'inventario.id_usuario', '=', 'user.id_usuarios')->where('id_producto', $id)->get();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
        return $inventario; 
    }

    public function obtenerPedidosProveedor($id){    
        try {
            $inventario = Inventario::select('inventario.*','pdt.nombre As producto', 'user.name')
            ->leftjoin('productos AS pdt', 'inventario.id_producto', '=', 'pdt.id_productos')
            ->leftjoin('unidades AS uni', 'pdt.id_unidad', '=', 'uni.id_unidades')
            ->leftjoin('proveedores AS prov', 'pdt.id_proveedor', '=', 'prov.id_proveedores')
            ->leftjoin('usuarios AS user', 'inventario.id_usuario', '=', 'user.id_usuarios')->where('estado', true)->where('id_proveedor', $id)->get();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
        return $inventario; 
    }

    public function obtenerInformacionInventarios(){
        try {
            $inventarios = Inventario::select('inventario.*', 'pdt.codigo', 'pdt.nombre AS producto','pdt.peso', 'prov.nombre AS proveedor', 'user.name','uni.abreviacion')
            ->leftjoin('productos AS pdt', 'inventario.id_producto', '=', 'pdt.id_productos')
            ->leftjoin('unidades AS uni', 'pdt.id_unidad', '=', 'uni.id_unidades')
            ->leftjoin('proveedores AS prov', 'pdt.id_proveedor', '=', 'prov.id_proveedores')
            ->leftjoin('usuarios AS user', 'inventario.id_usuario', '=', 'user.id_usuarios')->where('pdt.estado_activacion', true)->where('prov.estado_activacion', true)->get();

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al traer la información de la base de datos'], 500);
        }
        return $inventarios; 
    }
}

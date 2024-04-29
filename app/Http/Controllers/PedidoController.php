<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidoCollection;
use Carbon\Carbon;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\PedidoProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new PedidoCollection(Pedido::with('user')->with('productos')->where('estado', 0)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrResponse = array(
            'status' => 0,
            'message' => null,
            'pedido' => null
        );

        try {
            DB::beginTransaction();
            // Almacenar informacion de pedido
            $pedido = new Pedido();
            $pedido->user_id = Auth::user()->id;
            $pedido->total = $request->total;
            $pedido->save();
            
            // Almacenar detalles de pedido
            $productos = $request->productos;
            $pedido_productos = array();

            foreach ($productos as $producto) {
                $pedido_productos[] = array(
                    'pedido_id' => $pedido['id'],
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
            }

            PedidoProducto::insert($pedido_productos);
            DB::commit();
            $arrResponse['status'] = 1;
            $arrResponse['message'] = 'Pedido realizado con éxito.';
            $arrResponse['pedido'] = $pedido;
        } catch (\Throwable $th) {
            DB::rollBack();
            $arrResponse['status'] = -1;
            $arrResponse['message'] = $th->getMessage();
        }

        return $arrResponse;
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $arrResponse = array(
            'status' => 0,
            'message' => null,
            'pedido' => null
        );

        $pedido->estado = 1;

       if ($pedido->save()) {
        $arrResponse['status'] = 1;
        $arrResponse['message'] = 'Pedido completado.';
        $arrResponse['pedido'] = $pedido;
       } else {
        $arrResponse['status'] = -1;
        $arrResponse['message'] = 'Error al intentar actualizar pedido.';
       }

       return $arrResponse;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}

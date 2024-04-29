<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoCollection;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProductoCollection(Producto::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $arrResponse = array(
            'status' => 0,
            'message' => null,
            'producto' => null
        );

        $producto->disponible = $producto->disponible ? 0 : 1;

       if ($producto->save()) {
        $arrResponse['status'] = 1;
        $arrResponse['message'] = 'Producto actualizado con éxito.';
        $arrResponse['producto'] = $producto;
       } else {
        $arrResponse['status'] = -1;
        $arrResponse['message'] = 'Error al intentar actualizar el producto.';
       }

       return $arrResponse;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}

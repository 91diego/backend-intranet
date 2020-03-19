<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupuestoCompra;

class SupuestoCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supuestoCompra = new SupuestoCompra();
        $supuestoCompra->nombre = $request->input('nombre');
        $supuestoCompra->inicio_plazo = $request->input('inicio_plazo');
        $supuestoCompra->fin_plazo = $request->input('fin_plazo');
        $supuestoCompra->tipo_compra = $request->input('tipo_compra');
        $supuestoCompra->desarrollo_id = $request->input('desarrollo_id');
        $supuestoCompra->save();
        echo json_encode($supuestoCompra);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoCompra::where('desarrollo_id', $id)->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoCompra::where('id', $id)->get();
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supuestoCompra = SupuestoCompra::find($id);
        $supuestoCompra->nombre = $request->input('nombre');
        $supuestoCompra->inicio_plazo = $request->input('inicio_plazo');
        $supuestoCompra->fin_plazo = $request->input('fin_plazo');
        $supuestoCompra->tipo_compra = $request->input('tipo_compra');
        $supuestoCompra->desarrollo_id = $request->input('desarrollo_id');
        $supuestoCompra->save();
        echo json_encode($supuestoCompra);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

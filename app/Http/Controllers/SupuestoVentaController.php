<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupuestoVenta;

class SupuestoVentaController extends Controller
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
        $supuestoVenta = new SupuestoVenta();
        $supuestoVenta->nombre = $request->input('nombre');
        $supuestoVenta->porcentaje_firma = $request->input('porcentaje_firma');
        $supuestoVenta->porcentaje_plazo = $request->input('porcentaje_plazo');
        $supuestoVenta->meses_plazo = $request->input('meses_plazo');
        $supuestoVenta->porcentaje_escritura = $request->input('porcentaje_escritura');
        $supuestoVenta->porcentaje_descuento = $request->input('porcentaje_descuento');
        $supuestoVenta->porcentaje_rendimiento = $request->input('porcentaje_rendimiento');
        $supuestoVenta->desarrollo_id = $request->input('desarrollo_id');
        $supuestoVenta->save();
        echo json_encode($supuestoVenta);
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
        return SupuestoVenta::where('desarrollo_id', $id)->orderBy('nombre', 'ASC')->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoVenta::where('id', $id)->get();
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
        $supuestoVenta = SupuestoVenta::find($id);
        $supuestoVenta->nombre = $request->input('nombre');
        $supuestoVenta->porcentaje_firma = $request->input('porcentaje_firma');
        $supuestoVenta->porcentaje_plazo = $request->input('porcentaje_plazo');
        $supuestoVenta->meses_plazo = $request->input('meses_plazo');
        $supuestoVenta->porcentaje_escritura = $request->input('porcentaje_escritura');
        $supuestoVenta->porcentaje_descuento = $request->input('porcentaje_descuento');
        $supuestoVenta->porcentaje_rendimiento = $request->input('porcentaje_rendimiento');
        $supuestoVenta->save();
        echo json_encode($supuestoVenta);
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

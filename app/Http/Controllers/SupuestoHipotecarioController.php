<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupuestoHipotecario;

class SupuestoHipotecarioController extends Controller
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
        $supuestoHipotecario = new SupuestoHipotecario();
        $supuestoHipotecario->porcentaje_comision_apertura = $request->input('porcentaje_comision_apertura');
        $supuestoHipotecario->porcentaje_enganche = $request->input('porcentaje_enganche');
        $supuestoHipotecario->duracion_credito = $request->input('duracion_credito');
        $supuestoHipotecario->tasa_interes = $request->input('tasa_interes');
        $supuestoHipotecario->tasa_extra = $request->input('tasa_extra');
        $supuestoHipotecario->repago_capital = $request->input('repago_capital');
        $supuestoHipotecario->porcentaje_descuento = $request->input('porcentaje_descuento');
        $supuestoHipotecario->desarrollo_id = $request->input('desarrollo_id');
        $supuestoHipotecario->save();
        echo json_encode($supuestoHipotecario);
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
        return SupuestoHipotecario::where('desarrollo_id', $id)->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoHipotecario::where('id', $id)->get();
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
        $supuestoHipotecario = SupuestoHipotecario::find($id);
        $supuestoHipotecario->porcentaje_comision_apertura = $request->input('porcentaje_comision_apertura');
        $supuestoHipotecario->porcentaje_enganche = $request->input('porcentaje_enganche');
        $supuestoHipotecario->duracion_credito = $request->input('duracion_credito');
        $supuestoHipotecario->tasa_interes = $request->input('tasa_interes');
        $supuestoHipotecario->tasa_extra = $request->input('tasa_extra');
        $supuestoHipotecario->repago_capital = $request->input('repago_capital');
        $supuestoHipotecario->porcentaje_descuento = $request->input('porcentaje_descuento');
        $supuestoHipotecario->save();
        echo json_encode($supuestoHipotecario);
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

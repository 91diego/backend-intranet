<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupuestoMercado;

class SupuestoMercadoController extends Controller
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
        $supuestoMercado = new SupuestoMercado();
        $supuestoMercado->valor_renta = $request->input('valor_renta');
        $supuestoMercado->costo_mantenimiento = $request->input('costo_mantenimiento');
        $supuestoMercado->costo_cierre = $request->input('costo_cierre');
        $supuestoMercado->inflacion = $request->input('inflacion');
        $supuestoMercado->desarrollo_id = $request->input('desarrollo_id');
        $supuestoMercado->save();
        echo json_encode($supuestoMercado);
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
        return SupuestoMercado::where('desarrollo_id', $id)->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoMercado::where('id', $id)->get();
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
        $supuestoMercado = SupuestoMercado::find($id);
        $supuestoMercado->valor_renta = $request->input('valor_renta');
        $supuestoMercado->costo_mantenimiento = $request->input('costo_mantenimiento');
        $supuestoMercado->costo_cierre = $request->input('costo_cierre');
        $supuestoMercado->inflacion = $request->input('inflacion');
        $supuestoMercado->save();
        echo json_encode($supuestoMercado);
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

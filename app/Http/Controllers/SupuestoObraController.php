<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupuestoObra;

class SupuestoObraController extends Controller
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
        $supuestoObra = new SupuestoObra();
        $supuestoObra->torre = $request->input('torre');
        $supuestoObra->duracion_meses = $request->input('duracion_meses');
        $supuestoObra->fecha_termino = $request->input('fecha_termino');
        $supuestoObra->desarrollo_id = $request->input('desarrollo_id');
        $supuestoObra->save();
        echo json_encode($supuestoObra);
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
        return SupuestoObra::where('desarrollo_id', $id)->orderBy('torre', 'ASC')->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return SupuestoObra::where('id', $id)->get();
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
        $supuestoObra = SupuestoObra::find($id);
        $supuestoObra->torre = $request->input('torre');
        $supuestoObra->duracion_meses = $request->input('duracion_meses');
        $supuestoObra->fecha_termino = $request->input('fecha_termino');
        $supuestoObra->desarrollo_id = $request->input('desarrollo_id');
        $supuestoObra->save();
        echo json_encode($supuestoObra);
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

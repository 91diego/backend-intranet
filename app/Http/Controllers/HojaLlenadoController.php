<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HojaLlenado;
use Illuminate\Support\Facades\DB;

class HojaLlenadoController extends Controller
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
        //
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
        //return HojaLlenado::where('desarrollo_id', $id)->get();
        $hojaLlenado = DB::TABLE('desarrollos')
        ->LEFTJOIN('supuesto_ventas', 'supuesto_ventas.desarrollo_id', '=', 'desarrollos.id')
        ->LEFTJOIN('prototipos', 'prototipos.desarrollo_id', '=', 'desarrollos.id')
        ->LEFTJOIN('pisos', 'pisos.desarrollo_id', '=', 'desarrollos.id')
        ->LEFTJOIN('plusvalias', 'plusvalias.desarrollo_id', '=', 'desarrollos.id')
        ->LEFTJOIN('supuesto_compras', 'supuesto_compras.desarrollo_id', '=', 'desarrollos.id')
        ->LEFTJOIN('supuesto_obras', 'supuesto_obras.desarrollo_id', '=', 'desarrollos.id')                
        // ->select('encuestas.nombre', 'encuestas.fecha_creacion', 'preguntas.numero', 'preguntas.pregunta', 'preguntas.abierta')
        ->WHERE('desarrollos.id', $id)->get();
        return json_encode($hojaLlenado);
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
        //
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

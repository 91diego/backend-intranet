<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prototipo;

class PrototipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prototipo = Prototipo::get();
        echo json_encode($prototipo);
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
        $prototipo = new Prototipo();
        $prototipo->prototipo = $request->input('prototipo');
        $prototipo->metros_cuadrados = $request->input('metros_cuadrados');
        $prototipo->costo_venta_metro_cuadrado = $request->input('costo_venta_metro_cuadrado');
        $prototipo->precio = $request->input('precio');
        $prototipo->desarrollo_id = $request->input('desarrollo_id');
        $prototipo->save();
        echo json_encode($prototipo);
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
        return Prototipo::where('desarrollo_id', $id)->orderBy('prototipo', 'ASC')->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return Prototipo::where('id', $id)->get();
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
        $prototipo = Prototipo::find($id);
        $prototipo->prototipo = $request->input('prototipo');
        $prototipo->metros_cuadrados = $request->input('metros_cuadrados');
        $prototipo->costo_venta_metro_cuadrado = $request->input('costo_venta_metro_cuadrado');
        $prototipo->precio = $request->input('precio');
        $prototipo->save();
        echo json_encode($prototipo);
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

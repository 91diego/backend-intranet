<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plusvalia;

class PlusvaliaController extends Controller
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
        $plusvalia = new Plusvalia();
        $plusvalia->porcentaje_plusvalia = $request->input('porcentaje_plusvalia');
        $plusvalia->desarrollo_id = $request->input('desarrollo_id');
        $plusvalia->save();
        echo json_encode($plusvalia);
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
        return Plusvalia::where('desarrollo_id', $id)->orderBy('porcentaje_plusvalia', 'ASC')->get();
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
        $plusvalia = Plusvalia::find($id);
        $plusvalia->porcentaje_plusvalia = $request->input('porcentaje_plusvalia');
        $plusvalia->save();
        echo json_encode($plusvalia);
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

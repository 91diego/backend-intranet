<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Desarrollo;
use App\Prototipo;

class DesarrolloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $desarrollos = Desarrollo::get();
        echo json_encode($desarrollos);
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
        //print_r($request);
        $desarrollo = new Desarrollo();
        $desarrollo->nombre = $request->input('nombre');
        $desarrollo->precio_inicial = $request->input('precio_inicial');
        $desarrollo->fecha_inicio_obra = $request->input('fecha_inicio_obra');
        $desarrollo->save();
        echo json_encode($desarrollo);
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
        // Desarrollo::SELECT('precio_inicial')->where('id', $id)->get();
        return Desarrollo::where('id', $id)->get();
    }

    public function details($id)
    {
        // SOLICITAMOS AL MODELO LOS DATOS CON EL ID ENVIADO
        return Desarrollo::where('id', $id)->get();
    }    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $movie_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // ACTUALIZA LA INFORMACION DEL MODELO
        $desarrollo = Desarrollo::find($id);
        $desarrollo->precio_inicial = $request->input('precio_inicial');
        $desarrollo->fecha_inicio_obra = $request->input('fecha_inicio_obra');
        $desarrollo->save();
        $proto = new Prototipo();
        $proto = DB::table('prototipos as a')
        ->join('desarrollos as c', 'a.desarrollo_id', '=', 'c.id')
        ->where('a.desarrollo_id', '=', $id)
        ->update(
            [ 
                'a.precio' => DB::raw('c.precio_inicial*a.metros_cuadrados')
            ]
        );
        echo json_encode($desarrollo);
        //$proto->save();
        /* UPDATE prototipos as p
        INNER JOIN desarrollos as d ON p.desarrollo_id = d.id
        SET p.precio = p.metros_cuadrados * d.precio_inicial
        WHERE p.desarrollo_id = 1*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $desarrollo = Desarrollo::find($id);
        $desarrollo->delete();
    }
}

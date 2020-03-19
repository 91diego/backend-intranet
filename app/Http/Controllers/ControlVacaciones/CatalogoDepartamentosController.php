<?php

namespace App\Http\Controllers\ControlVacaciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CatalogoDepartamentos;

class CatalogoDepartamentosController extends Controller
{
    private $bitrixSite;
    private $bitrixToken;

    public function __construct()
    {
        $this->bitrixSite=env('BITRIX_SITE', '');
        $this->bitrixToken = env('BITRIX_TOKEN', '');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Inserciones a la base de datos';
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
        return Request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function datosBitrix($name) 
    {
        // INSTANCIA DEL MODELO
        $departamentos = new CatalogoDepartamentos;

        // URL DEPARTAMENTOS
        $urlDepartments = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/'.$name.'.get';

        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($urlDepartments);
        $datos = json_decode($responseAPI, true);

        // ALMACENA LA INFORMACION QUE SE INSERTARA EN LA BASE DE DATOS
        $informacionDepartamentos = [];

        // SE RECORRE EL ARRAY QUE CONTIENE LA RESPUESTA DE LA API
        for ($i = 0; $i < count($datos['result']); $i++) {

            if ( array_key_exists('UF_HEAD', $datos['result'][$i]) &&
            array_key_exists('PARENT', $datos['result'][$i]) ) {

                $detallesDepartamento = [
                    'id' => $datos['result'][$i]['ID'],
                    'name' => $datos['result'][$i]['NAME'],
                    'parent' => (int) $datos['result'][$i]['PARENT'],
                    'uf_head' => (int) $datos['result'][$i]['UF_HEAD']
                ];
            } else {
                $detallesDepartamento = [
                    'id' => $datos['result'][$i]['ID'],
                    'name' => $datos['result'][$i]['NAME'],
                    'parent' => 0,
                    'uf_head' => 44
                ];
            }

            array_push($informacionDepartamentos, $detallesDepartamento);
        }
        
        /* 
            SE RECORRE EL ARRAY QUE CONTIENE LA RESPUESTA DE LA API
            SE INSERTAN REGISTROS SI NO EXISTEN
            SE ACTUALIZA LA INFORMACION
        */
        for ($j = 0; $j < count($informacionDepartamentos); $j++) {

            // INSERTA REGISTROS SI NO EXISTEN
            $data = CatalogoDepartamentos::firstOrCreate(
                [
                    'id_depto_crm' => $informacionDepartamentos[$j]['id']
                ],
                [
                    'id_depto_crm' => $informacionDepartamentos[$j]['id'],
                    'nombre' => $informacionDepartamentos[$j]['name'],
                    'id_depto_padre_crm' => $informacionDepartamentos[$j]['parent'],
                    'id_depto_responsable_crm' => $informacionDepartamentos[$j]['uf_head']
                ]
            );

            // ACTUALIZA LOS REGISTROS DE LA TABLA
            CatalogoDepartamentos::WHERE('id_depto_crm', '=', $informacionDepartamentos[$j]['id'])->UPDATE(array(
                'id_depto_crm' => $informacionDepartamentos[$j]['id'],
                'nombre' => $informacionDepartamentos[$j]['name'],
                'id_depto_padre_crm' => $informacionDepartamentos[$j]['parent'],
                'id_depto_responsable_crm' => $informacionDepartamentos[$j]['uf_head']
            ));

        }
        // return $informacionDepartamentos;
        return $data;
        //return $datos['result'];
    }
}

<?php

namespace App\Http\Controllers\ControlVacaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Usuarios;

class UsuariosController extends Controller
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
        $usuarios = new Usuarios;

        // URL DEPARTAMENTOS
        $urlUsers = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/'.$name.'.get';

        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($urlUsers);
        $datos = json_decode($responseAPI, true);

        $numeroRegistros = $datos['total'];

        // GUARDA EL LISTADO DE LOS USUARIOS
        $listaUsuarios = [];
        $idUsuario = $datos['result'][0]['ID'];
        //echo $numeroRegistros; exit;
        set_time_limit(7000);
        for($i = 0; $i <= $numeroRegistros; $i++) {
            
            // $idUsuario = $datos['result'][$i]['ID'];
            $urlListaUsuarios = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/'.$name.'.get.json?ID='.$idUsuario;  
            $headers = get_headers($urlListaUsuarios);

            $jsonListaUsuarios = file_get_contents($urlListaUsuarios);
            $lista = json_decode($jsonListaUsuarios, true);
            if ($headers[0] == "HTTP/1.1 200 OK") {

				$jsonListaUsuarios = file_get_contents($urlListaUsuarios);
                $lista = json_decode($jsonListaUsuarios, true);
                // print_r($lista['result']);
                if (empty($lista['result'])) {
                    $i--;
                } else {
                    array_push($listaUsuarios, $lista);
                }
            }
            $idUsuario++;
        }

        // ALMACENA LA INFORMACION QUE SE INSERTARA EN LA BASE DE DATOS
        $informacionUsuarios = [];

        // print_r($listaUsuarios); exit;
        $idDepartamentoAux;
        for ($j = 0; $j < count($listaUsuarios); $j++) {

            // print_r($listaUsuarios[$j]['result']);
            if (empty($listaUsuarios[$j]['result'][0]['UF_DEPARTMENT'])) {
                $idDepartamentoAux = 0;
            } else {
                $idDepartamentoAux = (int) $listaUsuarios[$j]['result'][0]['UF_DEPARTMENT'][0];
            }
            $detallesUsuario = array(
                'id' => $listaUsuarios[$j]['result'][0]['ID'],
                'nombre' => $listaUsuarios[$j]['result'][0]['NAME'],
                'apellido' => $listaUsuarios[$j]['result'][0]['LAST_NAME'],
                'email' => $listaUsuarios[$j]['result'][0]['EMAIL'],
                'empresa' => $listaUsuarios[$j]['result'][0]['WORK_COMPANY'],
                'puesto' => $listaUsuarios[$j]['result'][0]['WORK_POSITION'],
                'estatus' => $listaUsuarios[$j]['result'][0]['ACTIVE'],
                'id_departamento' => $idDepartamentoAux,
                'id_vacaciones' => 0
            );
            array_push($informacionUsuarios, $detallesUsuario);
        }

        /* 
            SE RECORRE EL ARRAY QUE CONTIENE LA RESPUESTA DE LA API
            SE INSERTAN REGISTROS SI NO EXISTEN
            SE ACTUALIZA LA INFORMACION
        */
        for ($j = 0; $j < count($informacionUsuarios); $j++) {

            // INSERTA REGISTROS SI NO EXISTEN
            $data = Usuarios::firstOrCreate(
                [
                    'id_usuario_crm' => $informacionUsuarios[$j]['id']
                ],
                [
                    'nombre' => $informacionUsuarios[$j]['nombre'],
                    'apellido' => $informacionUsuarios[$j]['apellido'],
                    'email' => $informacionUsuarios[$j]['email'],
                    'empresa' => $informacionUsuarios[$j]['empresa'],
                    'puesto' => $informacionUsuarios[$j]['puesto'],
                    'estatus' => $informacionUsuarios[$j]['estatus'],
                    'id_usuario_crm' => $informacionUsuarios[$j]['id'],
                    'id_vacaciones' => $informacionUsuarios[$j]['id_vacaciones'],
                    'id_depto_crm' => $informacionUsuarios[$j]['id_departamento'],
                ]
            );

            // ACTUALIZA LOS REGISTROS DE LA TABLA
            Usuarios::WHERE('id_usuario_crm', '=', $informacionUsuarios[$j]['id'])->UPDATE(
                array(
                    'nombre' => $informacionUsuarios[$j]['nombre'],
                    'apellido' => $informacionUsuarios[$j]['apellido'],
                    'email' => $informacionUsuarios[$j]['email'],
                    'empresa' => $informacionUsuarios[$j]['empresa'],
                    'puesto' => $informacionUsuarios[$j]['puesto'],
                    'estatus' => $informacionUsuarios[$j]['estatus'],
                    'id_usuario_crm' => $informacionUsuarios[$j]['id'],
                    'id_vacaciones' => $informacionUsuarios[$j]['id_vacaciones'],
                    'id_depto_crm' => $informacionUsuarios[$j]['id_departamento']
                )
            );

        }

        return $data;
    }
}

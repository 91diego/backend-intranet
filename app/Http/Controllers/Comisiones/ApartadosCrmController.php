<?php

namespace App\Http\Controllers\Comisiones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApartadosCrm;

class ApartadosCrmController extends Controller
{
    private $bitrixSite;
    private $bitrixToken;

    public function __construct()
    {
        $this->bitrixSite=env('BITRIX_SITE', '');
        $this->bitrixToken = env('BITRIX_TOKEN', '');
    }

    /**
     * Obtiene la fase de la negociacion
     * @param  int  $id_deal
     */
    public function faseNegociacion($id_deal) {

        // URL PARA OBTENER INFORMACION DEL DEAL
        $urlDeals = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/crm.deal.get?ID='.$id_deal;
        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($urlDeals);
        $data = json_decode($responseAPI, true);

        /**
         * [STAGE_ID]= 1 -> VISITA
         * [STAGE_ID]= 5 -> CITA PROGRAMADA
         * [STAGE_ID]= NEW -> PRECALIFICADO
         * [STAGE_ID]= 2 -> PROCESO DE APARTADO
         * [STAGE_ID]= 6 -> APARTADO
         */
        if ($data['result']['STAGE_ID'] == "NEW" || $data['result']['STAGE_ID'] == 1
        || $data['result']['STAGE_ID'] == 5 || $data['result']['STAGE_ID'] == 2) {

            echo "Cambia la fase";
            
            /* SE REALIZA EL UPDATE A LA TABLA DE APARTADOS 
            Y CAMBIAMOS EL VALOR DEL ESTATUS A 0 */

            // ACTUALIZA LOS REGISTROS DE LA TABLA
            ApartadosCrm::WHERE('id_negociacion', '=', $id_deal)->UPDATE(array(

                'id_lead' => $data['result']['LEAD_ID'],
                'nombre_negociacion' => $data['result']['TITLE'],
                'producto1' => $data['result']['UF_CRM_1573063908'],
                'producto2' => $data['result']['UF_CRM_1573064054413'],
                'total' => $data['result']['UF_CRM_1573066384206'],
                'precio_producto' => $data['result']['OPPORTUNITY'],
                'estatus_apartado' => 0,
                'id_responsable' => $data['result']['ASSIGNED_BY_ID']
            ));
            
            // SI LA NEGOCIACION CAE EN LA FASE DE APARTADO
        } elseif ($data['result']['STAGE_ID'] == 6) {
            
            /* SI EL REGISTRO NO EXISTE, SE INSERTA
            DE LO CONTRARIO SE ACTUALIZA LA INFORMACION */
            // INSERTA REGISTROS SI NO EXISTEN
            $info = ApartadosCrm::firstOrCreate(
                [
                    'id_negociacion' => $id_deal
                ],
                [
                    'id_negociacion' => $id_deal,
                    'id_lead' => $data['result']['LEAD_ID'],
                    'nombre_negociacion' => $data['result']['TITLE'],
                    'producto1' => $data['result']['UF_CRM_1573063908'],
                    'producto2' => $data['result']['UF_CRM_1573064054413'],
                    'total' => $data['result']['UF_CRM_1573066384206'],
                    'precio_producto' => $data['result']['OPPORTUNITY'],
                    'estatus_apartado' => 0,
                    'id_responsable' => $data['result']['ASSIGNED_BY_ID']
                ]
            );

            // ACTUALIZA LOS REGISTROS DE LA TABLA
            ApartadosCrm::WHERE('id_negociacion', '=', $id_deal)->UPDATE(array(

                'id_lead' => $data['result']['LEAD_ID'],
                'nombre_negociacion' => $data['result']['TITLE'],
                'producto1' => $data['result']['UF_CRM_1573063908'],
                'producto2' => $data['result']['UF_CRM_1573064054413'],
                'total' => $data['result']['UF_CRM_1573066384206'],
                'precio_producto' => $data['result']['OPPORTUNITY'],
                'estatus_apartado' => 1,
                'id_responsable' => $data['result']['ASSIGNED_BY_ID']
            ));
        }

    }
    /**
     * Obtiene e inserta los apartados del crm
     *
     */
    public function apartadosCrm() {

        // URL NEGOCIACIONES EN ETAPA DE APARTADO
        $urlDeals = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/crm.deal.list?ORDER[ID]=ASC&FILTER[CATEGORY_ID]=0&FILTER[STAGE_ID]=6';
        // PRIMER ID DEL LISTADO
        $id = 0;
        // TAMAÑO DE LA MUESTRA
        $tam = 0;
        // ALMACENA DATOS DE LA MUESTRA
        $arrayApartados = [];

        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($urlDeals);
        $datos = json_decode($responseAPI, true);
        
        $tam = $datos['total'];

        if ($tam <= 50) {
            for ($i=0; $i < $tam; $i++) {
                $id = $datos['result'][$i]['ID'];
                $detailsDeal = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/crm.deal.get?ID='.$id;
                // OBTIENE LA RESPUESTA DE LA API REST BITRIX
                $response = file_get_contents($detailsDeal);
                $data = json_decode($response, true);
                array_push($arrayApartados, [
                    "id_negociacion" => $data['result']['ID'],
                    "id_lead" => $data['result']['LEAD_ID'],
                    "nombre_negociacion" => $data['result']['TITLE'],
                    "id_responsable" => $data['result']['ASSIGNED_BY_ID'],
                    "producto1" => $data['result']['UF_CRM_1573063908'],
                    "producto2" => $data['result']['UF_CRM_1573064054413'],
                    "total" => $data['result']['UF_CRM_1573066384206'],
                    "precio_producto" => $data['result']['OPPORTUNITY'],
                    "estatus_apartado" => 1
                ]);
            }
        }

                /* 
            SE RECORRE EL ARRAY QUE CONTIENE LA RESPUESTA DE LA API
            SE INSERTAN REGISTROS SI NO EXISTEN
            SE ACTUALIZA LA INFORMACION
        */
        for ($j = 0; $j < count($arrayApartados); $j++) {

            // INSERTA REGISTROS SI NO EXISTEN
            $data = ApartadosCrm::firstOrCreate(
                [
                    'id_negociacion' => $arrayApartados[$j]['id_negociacion']
                ],
                [
                    'id_negociacion' => $arrayApartados[$j]['id_negociacion'],
                    'id_lead' => $arrayApartados[$j]['id_lead'],
                    'nombre_negociacion' => $arrayApartados[$j]['nombre_negociacion'],
                    'producto1' => $arrayApartados[$j]['producto1'],
                    'producto2' => $arrayApartados[$j]['producto2'],
                    'total' => $arrayApartados[$j]['total'],
                    'precio_producto' => $arrayApartados[$j]['precio_producto'],
                    'estatus_apartado' => $arrayApartados[$j]['estatus_apartado'],
                    'id_responsable' => $arrayApartados[$j]['id_responsable']
                ]
            );

            // ACTUALIZA LOS REGISTROS DE LA TABLA
            ApartadosCrm::WHERE('id_negociacion', '=', $arrayApartados[$j]['id_negociacion'])->UPDATE(array(
                'id_negociacion' => $arrayApartados[$j]['id_negociacion'],
                'id_lead' => $arrayApartados[$j]['id_lead'],
                'nombre_negociacion' => $arrayApartados[$j]['nombre_negociacion'],
                'producto1' => $arrayApartados[$j]['producto1'],
                'producto2' => $arrayApartados[$j]['producto2'],
                'total' => $arrayApartados[$j]['total'],
                'precio_producto' => $arrayApartados[$j]['precio_producto'],
                'estatus_apartado' => $arrayApartados[$j]['estatus_apartado'],
                'id_responsable' => $arrayApartados[$j]['id_responsable']
            ));
        }
        return $data;
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
}

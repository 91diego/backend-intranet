<?php

namespace App\Http\Controllers\Comisiones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
         * [STAGE_ID]= 2 -> APARTADO
         */
        if ($data['result']['STAGE_ID'] == "NEW" || $data['result']['STAGE_ID'] == 1
        || $data['result']['STAGE_ID'] == 5) {
            
            return "ESTATUS";
            /* SE REALIZA EL UPDATE A LA TABLA DE APARTADOS 
            Y CAMBIAMOS EL VALOR DEL ESTATUS A 0 */
        } elseif ($data['result']['STAGE_ID'] == 2) {
            
            /* SI EL REGISTRO NO EXISTE, SE INSERTA
            DE LO CONTRARIO SE ACTUALIZA LA INFORMACION */
            return $data['result']['STAGE_ID'];
        }
        // return $data['result']['STAGE_ID'];

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
                    "responsable" => $data['result']['ASSIGNED_BY_ID'],
                    "producto1" => $data['result']['UF_CRM_1573063908'],
                    "producto2" => $data['result']['UF_CRM_1573064054413'],
                    "total" => $data['result']['UF_CRM_1573066384206'],
                    "precio_producto" => $data['result']['OPPORTUNITY'],
                    "estatus_apartado" => 1
                ]);
            }
        }
        return $arrayApartados;
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

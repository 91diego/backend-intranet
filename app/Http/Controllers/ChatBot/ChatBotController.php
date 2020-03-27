<?php

namespace App\Http\Controllers\chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatBotController extends Controller
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
        return 'MI API';
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

    /**
     * @param string $name -> NOMBRE
     * @param string $lastName -> APELLIDO
     * @param int $phone -> TELEFONO
     * @param string $email -> CORREO ELECTRONICO
     * @param string $purchase -> MOTIVO DE COMPRA
     * @param string $source -> ORIGEN
     * @param string $zone -> DESARROLLO
     * @param string $sale -> CANAL DE VENTAS
     */
    public function newLead($name, $lastName, $phone, $email, $purchase, $zone){
    
        // URL LEADS
        $urlAddLead = $this->bitrixSite.'/rest/117/'.$this->bitrixToken.'/crm.lead.add';
        // 
        
        /*
            CAMBIA EL NOMBRE DEL MOTIVO DE COMPRA PARA QUE SEA IGUAL AL DE BITRIX
            ASIGNA EL ID DE ACUERDO EL MOTIVO DE COMPRA
        */
        $purchaseId = 0;
        if (ucfirst($purchase) == "Vivir") {

            $purchase = "Habitar";
            $purchaseId = 26;
        } elseif(ucfirst($purchase) == "Invertir") {

            $purchaseId = 27;
        } elseif(ucfirst($purchase) == "Habitar") {

            $purchaseId = 26;
        }

        /* 
            ASIGNA EL ID CORRESPONDIENTE DE ACUERDO AL DESARROLLO
        */
        $zoneId = 0;
        switch (strtoupper($zone)) {
            case 'ANUVA':
                $zoneId = 278;
                break;
            case 'BRASILIA':
                $zoneId = 151;
                break;
            case 'LIVA':
                $zoneId = 150;
                break;
            case 'SANTILLANA COTO 8':
                $zoneId = 279;
                break;
            case 'COTO 4 ARR HAB':
                $zoneId = 280;
                break;
            case 'LOS NISPEROS':
                $zoneId = 277;
                break;
            case 'ATTALA':
                $zoneId = 276;
                break;
        }

        /*  
            DATOS DEL LEAD
            EL ORIGEN Y EL CANAL DE VENTAS SON FIJOS:
            ORIGEN -> Chatbot, CANAL DE VENTAS -> Marketing
        */
        $dataLead = [

            "nombre" => $name,
            "apellido" => $lastName,
            "telefono" => $phone,
            "email" => $email,
            "motivo_compra" => $purchaseId,
            "desarrollo" => $zoneId,
            "canal_ventas" => 51, // Marketing 51, Comercial 50
            "origen" => 20 // "EMAIL" -> Facebook, 20 -> Chatbot
        ];

	    $queryData = http_build_query(
            array(
                'fields' => array(
                    "NAME" => $dataLead['nombre'], // NOMBRE DEL LEAD
                    "LAST_NAME" => $dataLead['apellido'], // APELLIDO DEL LEAD
                    "PHONE" => array(
                        'fields' => array(
                            "VALUE_TYPE" => "WORK",
                            "VALUE" => $dataLead['telefono'],
                            "TYPE_ID" => "PHONE"
                            ),
                        ),// TELEFONO DEL LEAD
                        "EMAIL" => array(
                            'fields' => array(
                                "VALUE_TYPE" => "WORK",
                                "VALUE" => $dataLead['email'],
                                "TYPE_ID" => "EMAIL"
                            ),
                        ), // EMAIL DEL LEAD       		
                        "UF_CRM_1559757849830" => $dataLead['motivo_compra'], // MOTIVO DE COMPRA
                        "SOURCE_ID" => $dataLead['origen'],// "EMAIL" corresponde a FB, // ORIGEN
                        "UF_CRM_1561502098252" => $dataLead['desarrollo'], // DESARROLLO, SE VALIDA CONTRA CATALOGO
                        "UF_CRM_1560363526781" => $dataLead['canal_ventas'] // 51 mkt CANAL DE VENTAS, CAMPO FIJO
                    ),
                    'params' => array("REGISTER_SONET_EVENT" => "Y")
                )
            );

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $urlAddLead,
        CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result);
    }
}

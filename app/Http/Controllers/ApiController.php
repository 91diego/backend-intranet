<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Laravel\Lumen\Routing\Controller as BaseController;
//use Log;
//use Illuminate\Support\Facades\File;

class ApiController extends BaseController
{
    private $bitrixSite;
    private $bitrixToken;
    public function __construct()
    {
        $this->bitrixSite=env('BITRIX_SITE', '');
        $this->bitrixToken = env('BITRIX_TOKEN', '');
    }
    public function getProfile()
    {
      /*  $client   = new \GuzzleHttp\Client();
        $response = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/profile');
        return $response->getBody();*/
        echo "golll";
    }
    /*public function getProducts()
    {
        $total=1;
        $ini=0;
        try {
            Log::info('Iniciando sincronización');
            while ($ini<$total) {
                $appParams = array(
                  "start" =>$ini
                );

                $client   = new \GuzzleHttp\Client();
                $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.product.list?'.http_build_query($appParams));
                $body = json_decode($res->getBody());
                foreach ($body->result as $key => $value) {
                    $this->updatePropierty($value->NAME, $value->ACTIVE);
                }
                if (isset($body->next)) {
                    $ini = $body->next;
                } else {
                    $ini= $body->total;
                }
                  $total=$body->total;
            }
            Log::info('Terminando sincronización');
            echo "Terminado";
        } catch (Exception $e) {
            Log::error('Excepción capturada: ', ['code', $e->getMessage()]);
        }
    }
    private function updatePropierty($name, $active)
    {
        $discontinued=($active=='Y')?1:0;
        $update=DB::table('vtiger_products')
            ->where('productname', 'like', $name)
            ->update(['discontinued' => $discontinued]);
    }
    public function getContacts(Request $request)
    {
        $total=1;
        $ini=0;
        echo $this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.contact.list?';
        while ($ini<$total) {
            $appParams = array(
              "ORDER" => array("ID" => "ASC"),
              "start" =>$ini
            );

            $client   = new \GuzzleHttp\Client();
            $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.contact.list?'.http_build_query($appParams));
            $body = json_decode($res->getBody());
            if (isset($body->next)) {
                $ini=$body->next;
            } else {
                $ini = $body->total;
            }
            $total=$body->total;
        }
    }
    public function addContact(Request $request)
    {
            $total=1;
            $ini=0;
        while ($ini<$total) {
              $appParams = array(
              "FIELDS"=>["NAME"=> "John",
                    "SECOND_NAME"=> "Lancelot",
                    "LAST_NAME"=> "Doe",
                    "OPENED"=> "Y",
                    "ASSIGNED_BY_ID"=> 1,
                    "TYPE_ID"=> "CLIENT"],
              );
              $client   = new \GuzzleHttp\Client();
              $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.contact.add?'.http_build_query($appParams));
               $body = json_decode($res->getBody());
               dd($body);
        }
    }
    public function getFileNeodata()
    {
      $total=1;
      $ini=0;
        echo "Iniciando exportación";
        $routeFiles=env('RUTA_ARCHIVOS', base_path().'/storage/neodata');
        $aData = ["hola"=>1, "path"=>$routeFiles];
        $fileName= "neodata_".date("Y-m-d_H_i_s").".csv";
        $csv = fopen($routeFiles.'/'.$fileName, 'w');
        $headers=['CLIENTE', 'Proyecto', 'Nombre(s)', 'Apellido paterno', 'Apellido materno', 'Medio por el que se enteró', 'Fecha de nacimiento', 'Sexo', 'Fecha de visita', 'Nombre cónyuge', 'Apellido paterno cónyuge', 'Apellido materno cónyuge', 'No de IMSS', 'No de ISSSTE', 'No de INFONAVIT', 'No de FOVISSSTE', 'No de AFORE', 'No de SAR', 'Ingreso anual', 'Teléfono celular', 'Calle', 'Número oficial', 'Número interior', 'Lote', 'Manzana', 'Colonia', 'Municipio / Delegación', 'Estado', 'Localidad', 'Código postal', 'Teléfono casa', 'Correo', 'Identificación', 'Folio identificación', 'Nacionalidad', 'Originario de', 'CURP', 'Estado civil', 'Regimen matrimonial', 'Economía', 'Casa', 'Mensaje'];
          fputcsv($csv, $headers);
        while ($ini<$total) {
              $appParams = array(
                "start" =>$ini
              );
              $client   = new \GuzzleHttp\Client();
              $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.lead.list?'.http_build_query($appParams));
              $body = json_decode($res->getBody());
            foreach ($body->result as $key => $value) {
                $aTitle = explode(":", $value->TITLE);
                $arrayValue = $this->getArrayValue($value->ID);
                  $aData=['',
                          $aTitle[0],
                          trim($value->NAME.' '.$value->SECOND_NAME),
                          trim($value->LAST_NAME),
                          '',
                          $this->getCatalog('ORIGIN',$value->SOURCE_ID),
                          $value->BIRTHDATE,
                          $this->getCatalog('UF_CRM_1559757849830', $arrayValue['UF_CRM_1559757849830']), //Sexo
                          '',
                          $arrayValue['UF_CRM_1560364483893'],//'Nombre cónyuge
                          '',
                          '',
                          '',//immss
                          '',//isste
                          '',//  'No de INFONAVIT',
                          '',//'No de FOVISSSTE',
                          '',//'No de AFORE',
                          '',//'No de SAR',
                          '',//''Ingreso anual',
                          isset($value->PHONE)?$value->PHONE->value:'' , //'Teléfono celular',
                          $value->ADDRESS.' '.$value->ADDRESS_2,//'Calle',
                          '',// 'Número oficial',
                          '',//'Número interior',
                          '',//'Lote',
                          '',//'Manzana',
                          '',//'Colonia',
                          $value->ADDRESS_CITY,//'Municipio / Delegación',
                          $value->ADDRESS_PROVINCE,// 'Estado',
                          $value->ADDRESS_CITY,//'Localidad',
                          $value->ADDRESS_POSTAL_CODE,//'Código postal',
                          isset($value->PHONE)?$value->PHONE->value:'' , //''Teléfono casa',
                          isset($value->PHONE)?$value->EMAIL->value:'' , //''Correo',
                          '',//'Identificación',
                          '',//'Folio identificación',
                          '',//'Nacionalidad',
                          '',//'Originario de',
                          '',//'CURP',
                          '',// 'Estado civil',
                          '',// 'Regimen matrimonial',
                          '',//'Economía',
                          '',// 'Casa',
                          '',// 'Mensaje'
                  ];
                  fputcsv($csv, $aData);
            }
            if (isset($body->next)) {
                $ini = $body->next;
            } else {
                $ini= $body->total;
            }
            echo   $total=$body->total;


        }
        fclose($csv);
        

        echo "terminado ";
    }
    private function getCatalog($name,$id){
        $aCatalog=DB::table('catalogs_bitrix')->where('name','=',$name)->select(['value','name'])->first();
        $aJson=json_decode($aCatalog->value);
        $data=[];
        foreach ($aJson as $key => $value) {

            $data[$value->ID] = $value->VALUE;
        }

        return isset($data[$id])?$data[$id]:$id;

    }

    private function getArrayValue($id)
    {
        $client   = new \GuzzleHttp\Client();
        $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.lead.get?id='.$id);
        $body = json_decode($res->getBody());
        $res =(array)$body->result;
        return $res;

    }
    public function getCatalogs()
    {
        $total=1;
        $ini=0;
        try {
            Log::info('Iniciando sincronización catalogs');
            DB::table('catalogs_bitrix')->truncate();
            while ($ini<$total) {
                $appParams = array(
                  "start" =>$ini
                );

                $client   = new \GuzzleHttp\Client();
                $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.deal.userfield.list?'.http_build_query($appParams));
                $body = json_decode($res->getBody());
                foreach ($body->result as $key => $value) {
                  $nameCatalog = $value->FIELD_NAME;
                  $listValueCatalog=isset($value->LIST)?json_encode($value->LIST):'';
                  $labelCatalog=$this->getLabelCatalog($value->ID);

                  DB::table('catalogs_bitrix')->insert([
                      ['name' => $nameCatalog, 'value' => $listValueCatalog, 'label'=>$labelCatalog, 'bitrix_id'=>$value->ID]
                  ]);
                }
                if (isset($body->next)) {
                    $ini = $body->next;
                } else {
                    $ini= $body->total;
                }
                  $total=$body->total;
            }
            DB::table('catalogs_bitrix')->insert([
                ['name' => 'ORIGIN', 'value' => '[{"ID": "STORE","VALUE":"Punto de venta"},{"ID": "WEB","VALUE":"Sitio Web"},{"ID": "ADVERTISING","VALUE":"Círculo Cálido"}]', 'label'=>'ORIGEN', 'bitrix_id'=>999]
            ]);

            Log::info('Terminando sincronización catalogs');
            echo "Terminado";
        } catch (Exception $e) {
            Log::error('Excepción capturada catalogs: ', ['code', $e->getMessage()]);
        }

    }
    private function getLabelCatalog($id){
      $client   = new \GuzzleHttp\Client();
      $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.deal.userfield.get?id='.$id);
      $body = json_decode($res->getBody());
      return  ($body->result->EDIT_FORM_LABEL->la)?$body->result->EDIT_FORM_LABEL->la:"";
    }
    private function getProductDeal($id){
      $client   = new \GuzzleHttp\Client();
      $res = $client->get($this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.deal.productrows.get?id='.$id);
      $body = json_decode($res->getBody());
      return  ($body->result)?$body->result[0]:null;
    }
    public function getDeals()
    {
        $total=1;
        $ini=0;
        //DB::table('deals_bitrix')->truncate();
        while ($ini<$total) {
            $appParams = [
              //"filter"=>["CATEGORY_ID"=> "1","STAGE_ID"=>"C1:NEW"],
              "filter"=>["NAME"=> "1"],
              "start" =>$ini
            ];
            $client   = new \GuzzleHttp\Client();
            $addressService=$this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.deal.list?'.http_build_query($appParams);
            dd($addressService);
            $res = $client->get($addressService);
            $body = json_decode($res->getBody());
            foreach ($body->result as $key => $value) {
                $productDeal=$this->getProductDeal($value->ID);
                if (isset($productDeal->PRODUCT_NAME)) {
                    DB::table('deals_bitrix')->updateOrInsert(
                        ['bitrix_id' => $value->ID],
                        ['json_deal' => json_encode($value),
                         'title_product' => $productDeal->PRODUCT_NAME,
                         'product_id' => $productDeal->ID,
                         'json_product'=>json_encode($productDeal),
                          ]
                    );
                }
            }
            if (isset($body->next)) {
                 $ini = $body->next;
            } else {
                 $ini= $body->total;
            }
            $total=$body->total;
        }

    }
    public function importNeodata()
    {

        $fileName=env('ARCHIVO_NEODATA', base_path().'/storage/neodata/neodata_fechas.txt');
        if (!file_exists($fileName)){
            echo "no se encuentra archivo";
            die();
        }
        $content = File::get($fileName);
        $this->getDeals();
        $csv = str_getcsv($content, chr(10));
        $aValores= explode(" ", $csv[1]);
        $cuenta=0;
        $aRecords=[];

        $aElementos=[];
        $cuenta=0;
        $encabezado=[];
        $cuenta=0;
        foreach ($aValores as $element) {
            $lenString = strlen($element);
            $encabezado[]=trim((string)substr($csv[0], $cuenta, $lenString));
            $cuenta+=($lenString+1);
        }
        foreach ($csv as $value) {
            $cuenta++;
            if ($cuenta<3) {
                continue;
            }
            $aElementos=[];
            $cuenta=0;
            foreach ($aValores as $key => $element) {
                $lenString = strlen($element);
                $aElementos[$encabezado[$key]]=trim((string)substr($value, $cuenta, $lenString));
                $cuenta+=($lenString+1);
            }
            $aRecords[] = $aElementos;
        }

        foreach ($aRecords as &$value) {
            $value['bitrix_id']=$this->getBitrixId($value['Vivienda']);
        }
        unset($aRecords[1]);
        unset($aRecords[0]);
          $registros=$aRecords;
          //print_r($registros);
        foreach ($registros as $key => $val) {

          $this->updateBitrixDeal($val);
        }
        echo "Terminado";
    }
    private function getBitrixId($title)
    {
         $record = DB::table('deals_bitrix')->where('title_product', '=', $title)->first();

         return isset($record->bitrix_id)?$record->bitrix_id:0;
    }
    private function parsingDataBitrix($data)
    {

      $appParams = [
        "id"=>$data['bitrix_id'],
        "fields"=>["UF_CRM_1562191592191"=> $data['FechaVenta'],
                   "UF_CRM_1561057654363"=> $data['FechaEscritura'],
                   "UF_CRM_1562191683058"=> $data['FechaEntrega'],
                   "UF_CRM_1565824759020"=> $data['FechaAvaluo'],
                   "UF_CRM_1561058528719"=> $data['FechaLiberacion'],
                   "UF_CRM_1561058306812"=> $data['Notaria'],
                   "UF_CRM_1561057803598"=> $data['NumeroEscritura'],
                   "STAGE_ID"=>($data['FechaEscritura']!="" && $data['FechaEscritura']!=NULL)?"C1:FINAL_INVOICE":"C1:NEW"
                  ]
      ];
      //print_r($appParams);
      return $appParams;
  
    }
    public function updateBitrixDeal($data)
    {
          $appParams = $this->parsingDataBitrix($data);

          if ($appParams['id']>0) {
            $client   = new \GuzzleHttp\Client();
            $addressService=$this->bitrixSite.'/rest/1/'.$this->bitrixToken.'/crm.deal.update?'.http_build_query($appParams);
            echo $addressService;
            $res = $client->get($addressService);
            $body = json_decode($res->getBody());
        //    print_r($body);
          }
    }*/
    //https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.contact.list?start=100
    //https://training.bitrix24.com/rest_help/crm/products/index.php
    //https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.lead.get?id=26978
    /*
    https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.lead.list
    https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.lead.userfield.list
    https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.lead.userfield.get?id=110
    https://intranet.idex.cc/rest/1/h5t0bp4cy6ruqwz3/crm.lead.get?id=26978
    */
}

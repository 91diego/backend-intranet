<?php

namespace App\Http\Controllers\EnvioMailsCobranza;

use App;
use App\Http\Controllers\Controller;
use App\Mail\NotificacionPagos;
use App\Mail\Notificaciones;
use App\SendMailCobranza;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Facade\FlareClient\View;

class SendMailCobranzaController extends Controller
{

    /**
     * Envia email con el reporte de cliente para entrega
     * @param array $data
     * @param string $pathPDF
     */
    public function sendReport($data, $pathPDF) {

        if(!empty($data)) {

            // REPORTE ENVIADO A CLEMENTE
            Mail::to('dgonzalez@idex.cc')
            // ->cc('dgonzalez@idex.cc')
            // ->bcc('jbasurto@idex.cc')
            ->send(new NotificacionPagos($data, $pathPDF));
        }
    }
    /**
     * Envia los emails
     * @param  array $data
     * @param  array $financial     
     * @param  int $numberRows
     * @param  string $type
     */
    public function emailSending($data, $financial, $numberRows, $pathPDF, $subject) {

        // ACUMULA EL TOTAL PAGADO SIEMPRE Y CUANDO EL MONTO PAGO SEA IGUAL A 0
        $totalPagado = 0;
        // UTLTIMO PAGO DEL CLIENTE
        $ultimoPago = 0;
        // ACUMALDO DEL SALDO VENCIDO
        $acumuladoSaldoVencido = 0;
        // ALMACENA LA FECHA INICIAL
        $fechaBase = '';
        // ALMACENA LA FECHA PARA COMPARAR
        $fechaComparacion = '';

        /* ENVIO DE CORREOS ALERTA PAGO */
        set_time_limit(100000);
        for ($i = 0; $i < $numberRows ; $i++) {

            // SE REESTABLECE EL VALOR CUANDO EL VALOR DE i CAMBIA
            // $x = 0;
            // GUARDA LA INFORMACION DE PAGOS DEL CLIENTE
            // SE DEBE LIMPIAR EL ARRAY PARA QUE NO ACUMULE LA INFORMACION
            $informacionPagos = [];

            for ($j = 0; $j < count($financial); $j++) {

                // ENVIAMOS LA DATA Y LA INFORMACION FINANCIERA CORRESPONDIENTE AL CLIENTE
                // ACUMULA TODOS LOS REGISTROS DEL CLIENTE
                //echo $data[$i]['id_cliente']."<br>";
                //echo $financial[$j]['id_cliente']."<br>";

                if ($data[$i]['id_cliente'] === $financial[$j]['id_cliente']) {

                    array_push($informacionPagos, [

                        "id_cliente" => $financial[$j]['id_cliente'],
                        "cliente" => $financial[$j]['cliente'],
                        "vivienda" => $financial[$j]['vivienda'],
                        "prototipo" => $financial[$j]['prototipo'],
                        "metros_cuadrados" => $financial[$j]['metros_cuadrados'],
                        "torre" => $financial[$j]['torre'],
                        "desarrollo" => $financial[$j]['desarrollo'],
                        "email" => $financial[$j]['email'],
                        "concepto" => $financial[$j]['concepto'],
                        "fecha_pago" => $financial[$j]['fecha_pago'],
                        "monto_pago" => $financial[$j]['monto_pago'],
                        "mes_contrato" => $financial[$j]['mes_contrato'],
                        "precio" => $financial[$j]['precio'],
                        "dias_antes_pago" => $financial[$j]['dias_antes_pago']                 
                    ]);
                }
            }

            // SE REESTABLECE VALOR
            $totalPagado = 0;
            $acumuladoSaldoVencido = 0;
            for ($x=0; $x < count($informacionPagos); $x++) {

                /* OBTIENE EL MONTO DEL ULTIMO PAGO REALIZADO POR EL CLIENTE;
                    SE COMPARA LA FECHA DE LA POSICION INICIAL CON LA FECHA DE LA SIGUIENTE
                    POSICION DEL ARRAY
                */
                $fechaBase = strtotime(date($informacionPagos[$x]['fecha_pago']));
                $pagoBase = (int)$informacionPagos[$x]['monto_pago'];
                // $fechaComparacion = strtotime(date($informacionPagos[$i + 1]['fecha_pago']));

                // SI EL VALOR DE X ES IGUAL AL TAMAÑO DEL ARRAY
                if ($x === (count($informacionPagos) - 1) ) {

                    $pago = (int)$informacionPagos[$x]['monto_pago'];
                    $fechaComparacion = strtotime(date($informacionPagos[$x]['fecha_pago']));
                } else {

                    $pago = (int)$informacionPagos[$x + 1]['monto_pago'];
                    $fechaComparacion = strtotime(date($informacionPagos[$x + 1]['fecha_pago']));
                }

                // SE OBTIENE EL ULTIMO PAGO
                if ( ( $pagoBase === 0 && ($fechaBase < $fechaComparacion) ) && ( $pago != 0 && ($fechaComparacion > $fechaBase) ) ) {

                    $ultimoPago = (int)$informacionPagos[$x]['mes_contrato'];
                }

                // ACUMULA EL TOTAL PAGADO SIEMPRE Y CUANDO EL MONTO PAGO SEA IGUAL A 0
                if ((int)$informacionPagos[$x]['monto_pago'] === 0) {

                    $totalPagado = $totalPagado + (int)$informacionPagos[$x]['mes_contrato'];
                }

                // ACUMULA EL TOTAL DE SALDO VENCIDO
                if ((int)$informacionPagos[$x]['monto_pago'] > 0 && (int)$informacionPagos[$x]['dias_antes_pago'] > 0) {
                    $acumuladoSaldoVencido = $acumuladoSaldoVencido + (int)$informacionPagos[$x]['monto_pago'];
                }
            }
            
            // ESTE EL EMIAL DEL CLIENTE => $data[$i]["email"]
            // Mail::to($data[$i]["email"])
            Mail::to('dgonzalez@idex.cc')
            // ->cc('dgonzalez@idex.cc')
            ->bcc('mrojas@idex.cc', 'cmata@idex.cc', 'bat@idex.cc', 'ti-sistemas@idex.cc', 'jbasurto@idex.cc')
            ->send(new Notificaciones($data[$i], $informacionPagos, $ultimoPago, $acumuladoSaldoVencido, $totalPagado, $pathPDF, $subject));
        }
    }

    public function sendingMail() {

        // GUARDA INFORMACION PARA ENVIAR POR EMAIL
        // NOTIFICACION 7 DIAS ANTES DEL PAGO
        $informacionAlertaPago = [];
        // NOTIFICACION 7 DIAS ATRASO
        $informacionAdvertenciaPago1 = [];
        // NOTIFICACION 30 DIAS ATRASO
        $informacionAdvertenciaPago2 = [];
        // NOTIFICACION DE PAGO 120 DIAS DE ATRASO
        $informacionAdvertenciaPago3 = [];

        // OBTENER INFORMACION DE PAGOS DEL CLIENTE NOTIFICACION DE PAGO
        $estadoCuentaN1 = [];
        // OBTENER INFORMACION DE PAGOS DEL CLIENTE ALERTA DE PAGO 1
        $estadoCuentaA1 = [];
        // OBTENER INFORMACION DE PAGOS DEL CLIENTE ALERTA DE PAGO 2
        $estadoCuentaA2 = [];
        // OBTENER INFORMACION DE PAGOS DEL CLIENTE ALERTA DE PAGO 3
        $estadoCuentaA3 = [];

        // ALMACENA LOS REGISTROS QUE TENGAN PLAN DEL CREDITO- 1
        $informacionClientesPlanCredito = [];

        $fileName=env('DATOS_COBRANZA', storage_path().'/cobranza/DatosCobranza.txt');
        // print_r(storage_path()); exit;
        
        if (!file_exists($fileName)){
            echo "no se encuentra archivo";
            die();
        }

        $content = File::get($fileName);
        $csv = str_getcsv($content, chr(10));
        $aValores= explode(" ", $csv[1]);

        $cuenta = 0;
        $aRecords = [];

        $aElementos = [];
        $cuenta = 0;
        $encabezado = [];
        $cuenta = 0;

        foreach ($aValores as $element) {
            $lenString = strlen($element);
            $encabezado[] = trim((string)substr($csv[0], $cuenta, $lenString));
            $cuenta+=($lenString+1);
        }

        foreach ($csv as $value) {
            $cuenta++;
            if ($cuenta < 3) {
                continue;
            }
            $aElementos=[];
            $cuenta = 0;
            foreach ($aValores as $key => $element) {
                $lenString = strlen($element);
                $aElementos[$encabezado[$key]]=trim((string)substr($value, $cuenta, $lenString));
                $cuenta+=($lenString+1);
            }
            $aRecords[] = $aElementos;
        }

        unset($aRecords[1]);
        unset($aRecords[0]);
        $registros = $aRecords;

        // NUMERO DE REGISTROS
        $totalRegistros = count($registros);
        // SE OBTIENE LA FECHA ACTUAL
        $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
        set_time_limit(100000);

        /* SE OBTIENE LOS REGISTROS QUE TENGAN CONCEPTO PLAN DEL CREDITO- 1 
           Y QUE SU FECHA DE ENTREGA SEA IGUAL A LA FECHA ACTUAL
        */
        for ($i = 2; $i < $totalRegistros; $i++) {

            $conceptoPagoCliente = $registros[$i]['Concepto'];
            /* SI EL CONCEPTO ES PLAN DEL CREDITO- 1 Y LA FECHA DEL REGISTRO ES IGUAL A LA FECHA ACTUAL,
            SE INSERTA EN EL ARRAY informacionClientesPlanCredito */
            if ($conceptoPagoCliente == 'PLAN DEL CREDITO- 1' && (strtotime($registros[$i]['FechaPlan']) == $fecha_actual) ) {

                array_push($informacionClientesPlanCredito, [

                    "id_cliente" => $registros[$i]['IdCliente'],
                    "cliente" => $registros[$i]['Cliente'],
                    "vivienda" => $registros[$i]['Vivienda'],
                    "prototipo" => $registros[$i]['Prototipo'],
                    "torre" => $registros[$i]['Torre'],
                    "desarrollo" => $registros[$i]['Desarrollo'],
                    "email" => $registros[$i]['Email'],
                    "concepto" => $registros[$i]['Concepto'],
                    "fecha_entrega" => $registros[$i]['FechaPlan'],
                    "monto_pago" => $registros[$i]['SaldoPendiente'],
                    "mes_contrato" => $registros[$i]['MesContrato'],
                    "dias_antes_pago" => $registros[$i]['DifDias']
                ]);
            }
        }

        for ($i = 2; $i < $totalRegistros; $i++) {

            // SE OBTIENE LA CANTIDAD DE DIAS RESTANTES
            $diferenciaDias = (int)$registros[$i]['DifDias'];
            $adeudoCliente = (float)$registros[$i]['SaldoPendiente'];

            // ENVIO 7 DIAS ANTES DE SU PAGO
            if ( ($diferenciaDias == -7 /* $diferenciaDias <= -1 && $diferenciaDias >= -2 */) && $adeudoCliente > 0 /* && $conceptoPagoCliente != 'PLAN DEL CREDITO- 1'*/)  {

                for ($j = 2; $j < $totalRegistros; $j++) {

                    if ($registros[$i]['IdCliente'] === $registros[$j]['IdCliente']) {

                        array_push($estadoCuentaN1, [

                            "id_cliente" => $registros[$j]['IdCliente'],
                            "cliente" => $registros[$j]['Cliente'],
                            "vivienda" => $registros[$j]['Vivienda'],
                            "prototipo" => $registros[$j]['Prototipo'],
                            "metros_cuadrados" => $registros[$j]['M2'],
                            "torre" => $registros[$j]['Torre'],
                            "desarrollo" => $registros[$j]['Desarrollo'],
                            "email" => $registros[$j]['Email'],
                            "concepto" => $registros[$j]['Concepto'],
                            "fecha_pago" => $registros[$j]['FechaPlan'],
                            "monto_pago" => $registros[$j]['SaldoPendiente'],
                            "precio" => $registros[$j]['PrecioReal'],
                            "mes_contrato" => $registros[$i]['MesContrato'],
                            "dias_antes_pago" => $registros[$j]['DifDias']
                        ]);
                    }
                    
                }

                // INFORMACION DE CLIENTES NOTIFICACION PAGO
                array_push($informacionAlertaPago, [

                    "id_cliente" => $registros[$i]['IdCliente'],
                    "cliente" => $registros[$i]['Cliente'],
                    "vivienda" => $registros[$i]['Vivienda'],
                    "prototipo" => $registros[$i]['Prototipo'],
                    "metros_cuadrados" => $registros[$i]['M2'],
                    "torre" => $registros[$i]['Torre'],
                    "desarrollo" => $registros[$i]['Desarrollo'],
                    "email" => $registros[$i]['Email'],
                    "concepto" => $registros[$i]['Concepto'],
                    "fecha_pago" => $registros[$i]['FechaPlan'],
                    "monto_pago" => $registros[$i]['SaldoPendiente'],
                    "precio" => $registros[$i]['PrecioReal'],
                    "mes_contrato" => $registros[$i]['MesContrato'],
                    "dias_antes_pago" => $registros[$i]['DifDias']
                ]);

                // RECORDATORIO DE PAGO 7 DIAS DESPUES DE SU PAGO SOLO SI TIENE ADEUDO
            } elseif ( $diferenciaDias == 7 && $adeudoCliente > 1 /*&& $conceptoPagoCliente != 'PLAN DEL CREDITO- 1'*/) {

                for ($j = 2; $j < $totalRegistros; $j++) {

                    if ($registros[$i]['IdCliente'] === $registros[$j]['IdCliente']) {

                        array_push($estadoCuentaA1, [

                            "id_cliente" => $registros[$j]['IdCliente'],
                            "cliente" => $registros[$j]['Cliente'],
                            "vivienda" => $registros[$j]['Vivienda'],
                            "prototipo" => $registros[$j]['Prototipo'],
                            "metros_cuadrados" => $registros[$j]['M2'],
                            "torre" => $registros[$j]['Torre'],
                            "desarrollo" => $registros[$j]['Desarrollo'],
                            "email" => $registros[$j]['Email'],
                            "concepto" => $registros[$j]['Concepto'],
                            "fecha_pago" => $registros[$j]['FechaPlan'],
                            "monto_pago" => $registros[$j]['SaldoPendiente'],
                            "precio" => $registros[$j]['PrecioReal'],
                            "mes_contrato" => $registros[$i]['MesContrato'],
                            "dias_antes_pago" => $registros[$j]['DifDias']
                        ]);
                    }
                }

                // INFORMACION DE CLIENTES ALERTA DE PAGO 1
                array_push($informacionAdvertenciaPago1, [

                    "id_cliente" => $registros[$i]['IdCliente'],
                    "cliente" => $registros[$i]['Cliente'],
                    "vivienda" => $registros[$i]['Vivienda'],
                    "prototipo" => $registros[$i]['Prototipo'],
                    "metros_cuadrados" => $registros[$i]['M2'],
                    "torre" => $registros[$i]['Torre'],
                    "desarrollo" => $registros[$i]['Desarrollo'],
                    "email" => $registros[$i]['Email'],
                    "concepto" => $registros[$i]['Concepto'],
                    "fecha_pago" => $registros[$i]['FechaPlan'],
                    "monto_pago" => $registros[$i]['SaldoPendiente'],
                    "precio" => $registros[$i]['PrecioReal'],
                    "mes_contrato" => $registros[$i]['MesContrato'],
                    "dias_antes_pago" => $registros[$i]['DifDias']
                ]);

                // NOTIFICACION DE PAGO 90 DIAS DESPUES SI AUN TIENE ADEUDO
            } elseif ( ($diferenciaDias == 90) && $adeudoCliente > 1 /*&& $conceptoPagoCliente != 'PLAN DEL CREDITO- 1'*/) {

                for ($j = 2; $j < $totalRegistros; $j++) {

                    if ($registros[$i]['IdCliente'] === $registros[$j]['IdCliente']) {

                        array_push($estadoCuentaA2, [

                            "id_cliente" => $registros[$j]['IdCliente'],
                            "cliente" => $registros[$j]['Cliente'],
                            "vivienda" => $registros[$j]['Vivienda'],
                            "prototipo" => $registros[$j]['Prototipo'],
                            "metros_cuadrados" => $registros[$j]['M2'],
                            "torre" => $registros[$j]['Torre'],
                            "desarrollo" => $registros[$j]['Desarrollo'],
                            "email" => $registros[$j]['Email'],
                            "concepto" => $registros[$j]['Concepto'],
                            "fecha_pago" => $registros[$j]['FechaPlan'],
                            "monto_pago" => $registros[$j]['SaldoPendiente'],
                            "precio" => $registros[$j]['PrecioReal'],
                            "mes_contrato" => $registros[$i]['MesContrato'],
                            "dias_antes_pago" => $registros[$j]['DifDias']
                        ]);
                    }   
                }

                // INFORMACION DE CLIENTES ALERTA DE PAGO 2
                array_push($informacionAdvertenciaPago2, [

                    "id_cliente" => $registros[$i]['IdCliente'],
                    "cliente" => $registros[$i]['Cliente'],
                    "vivienda" => $registros[$i]['Vivienda'],
                    "prototipo" => $registros[$i]['Prototipo'],
                    "metros_cuadrados" => $registros[$i]['M2'],
                    "torre" => $registros[$i]['Torre'],
                    "desarrollo" => $registros[$i]['Desarrollo'],
                    "email" => $registros[$i]['Email'],
                    "concepto" => $registros[$i]['Concepto'],
                    "fecha_pago" => $registros[$i]['FechaPlan'],
                    "monto_pago" => $registros[$i]['SaldoPendiente'],
                    "precio" => $registros[$i]['PrecioReal'],
                    "dias_antes_pago" => $registros[$i]['DifDias']
                ]);

                // NOTIFICACION DE PAGO 120 DIAS DESPUES SI AUN TIENE ADEUDO
            } elseif ( ($diferenciaDias == 120) && $adeudoCliente > 1 /*&& $conceptoPagoCliente != 'PLAN DEL CREDITO- 1'*/) {

                for ($j = 2; $j < $totalRegistros; $j++) {

                    if ($registros[$i]['IdCliente'] === $registros[$j]['IdCliente']) {

                        array_push($estadoCuentaA3, [

                            "id_cliente" => $registros[$j]['IdCliente'],
                            "cliente" => $registros[$j]['Cliente'],
                            "vivienda" => $registros[$j]['Vivienda'],
                            "prototipo" => $registros[$j]['Prototipo'],
                            "metros_cuadrados" => $registros[$j]['M2'],
                            "torre" => $registros[$j]['Torre'],
                            "desarrollo" => $registros[$j]['Desarrollo'],
                            "email" => $registros[$j]['Email'],
                            "concepto" => $registros[$j]['Concepto'],
                            "fecha_pago" => $registros[$j]['FechaPlan'],
                            "monto_pago" => $registros[$j]['SaldoPendiente'],
                            "precio" => $registros[$j]['PrecioReal'],
                            "mes_contrato" => $registros[$i]['MesContrato'],
                            "dias_antes_pago" => $registros[$j]['DifDias']
                        ]);
                    }   
                }

                // INFORMACION DE CLIENTES ALERTA DE PAGO 2
                array_push($informacionAdvertenciaPago3, [

                    "id_cliente" => $registros[$i]['IdCliente'],
                    "cliente" => $registros[$i]['Cliente'],
                    "vivienda" => $registros[$i]['Vivienda'],
                    "prototipo" => $registros[$i]['Prototipo'],
                    "metros_cuadrados" => $registros[$i]['M2'],
                    "torre" => $registros[$i]['Torre'],
                    "desarrollo" => $registros[$i]['Desarrollo'],
                    "email" => $registros[$i]['Email'],
                    "concepto" => $registros[$i]['Concepto'],
                    "fecha_pago" => $registros[$i]['FechaPlan'],
                    "monto_pago" => $registros[$i]['SaldoPendiente'],
                    "precio" => $registros[$i]['PrecioReal'],
                    "dias_antes_pago" => $registros[$i]['DifDias']
                ]);
            }
        }

        // TOTAL DE REGISTROS
        $totalInformacionAlertaPago = count($informacionAlertaPago);
        $totalAdvertenciaPago1 = count($informacionAdvertenciaPago1);
        $totalAdvertenciaPago2 = count($informacionAdvertenciaPago2);
        $totalAdvertenciaPago3 = count($informacionAdvertenciaPago3);

        // ENVIO DE EMAILS
        $pathReportePDF = '';
        $pathReportePDF = storage_path().'\cobranza\pdf\reporte_plan_credito.pdf';
        $this->path = storage_path().'\cobranza\pdf\edo_cuenta.pdf';
        set_time_limit(100000);
        $this->emailSending($informacionAlertaPago, $estadoCuentaN1, $totalInformacionAlertaPago, $this->path, "Estimado cliente, le recordamos que su proxima fecha de pago es en 7 dias.");
        $this->emailSending($informacionAdvertenciaPago1, $estadoCuentaA1, $totalAdvertenciaPago1, $this->path, "Estimado cliente, le recordamos que existe un atraso de 7 dias en su pago.");
        $this->emailSending($informacionAdvertenciaPago2, $estadoCuentaA2, $totalAdvertenciaPago2, $this->path, "Estimado cliente, le recordamos que existe un atraso de 90 dias en su pago.");
        $this->emailSending($informacionAdvertenciaPago3, $estadoCuentaA3, $totalAdvertenciaPago3, $this->path, "Estimado cliente, le recordamos que existe un atraso de 120 dias en su pago.");

        /* ENVIO DEL REPORTE CON LOS CLIENTES PROXIMOS A ENTREGAR.
        SI EL ARRAY NO CONTIENE INFORMACION, NO SE REALIZA NINGUNA ENVIO. */
        $this->sendReport($informacionClientesPlanCredito, $pathReportePDF);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

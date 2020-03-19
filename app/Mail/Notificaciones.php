<?php

namespace App\Mail;

use App;
use App\SendMailCobranza;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Date\Date;

class Notificaciones extends Mailable
{
    use Queueable, SerializesModels;
    public $sendMailCobranza;
    public $informacionPagos;
    public $pdf;
    public $referencia;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $financial, $ultimoPago, $saldoVencido, $totalPagado, $pathPDF)
    {
        // Array para almacenar la informacion del estado de cuenta
        $layoutEstadoCuenta = [];
        // Contiene el logo del desarrollo
        $logoDesarrollo = "";
        $this->sendMailCobranza = $data;
        $this->informacionPagos = $financial;
        $this->path = $pathPDF;
 
        set_time_limit(100000);
        for ($i=1; $i < count($this->informacionPagos); $i++) {
            
            $estatusPago = "";
            $diferenciaDias = (int)$this->informacionPagos[$i]['dias_antes_pago'];
            $adeudoCliente = (float)$this->informacionPagos[$i]['monto_pago'];

            $estatusPago = $this->informacionPagos[$i]['monto_pago'];

            // SI EXISTE MONTO A PAGAR Y LA DIFERENCIA DE DIAS ES IGUAL 
            // O MAYOR A 90 DIAS, ES ATRASO
            if ( ($diferenciaDias >= 90) && $adeudoCliente > 0 ) {

                array_push($layoutEstadoCuenta, [
                    'numero_pago' => $i,
                    'fecha_pago' => $this->informacionPagos[$i]['fecha_pago'],
                    'monto_pago' => $estatusPago,
                    'estatus' => 'ATRASO',
                    'dias_siguiente_pago' => $this->informacionPagos[$i]['dias_antes_pago'],
                ]);
            } else {

                array_push($layoutEstadoCuenta, [
                    'numero_pago' => $i,
                    'fecha_pago' => $this->informacionPagos[$i]['fecha_pago'],
                    'monto_pago' => $estatusPago,
                    'dias_siguiente_pago' => $this->informacionPagos[$i]['dias_antes_pago'],
                ]);
            }
        }

        // SE ASIGNA EL LOGOTIPO DEL DESARROLLO EN EL ESTADO DE CUENTA
        if ($this->informacionPagos[0]['desarrollo'] === "Anuva") {
            // $logoDesarrollo = asset('images/Anuva.jpg');
            $logoDesarrollo = base_path().'\public\images\Anuva.jpg';
        }
   
        /* DATOS GENERALES */
        // SE OBTIENE LA ULTIMA POSICION DEL ARRAY
        setlocale(LC_ALL,"es");
        $acumuladoPagos = $totalPagado;
        $nombreCliente = $this->sendMailCobranza["cliente"];
        $nombreClienteReferencia = explode(' ', $nombreCliente);
        $vivienda = $this->sendMailCobranza["vivienda"];
        $viviendaReferencia = explode('-', $vivienda);
        $prototipo = $this->sendMailCobranza["prototipo"];
        $torre = $this->sendMailCobranza["torre"];
        $desarrollo = $this->sendMailCobranza["desarrollo"];
        $importePago = $this->sendMailCobranza["monto_pago"];
        $precioVivienda = $this->sendMailCobranza["precio"];
        $fechaProximoPago = $this->sendMailCobranza["fecha_pago"];
        $ultimoPagoPDF = $ultimoPago;
        $acumuladoSaldoVencido = $saldoVencido;
        $referenciaPago = $viviendaReferencia[3].' '.$nombreClienteReferencia[0].''.$nombreClienteReferencia[2];
        $this->referencia = $referenciaPago;
        /* FIN DATOS GENERALES */

        // GENERACION DEL PDF
        $pdfEstadoCuenta = '';
        $pdf = App::make('dompdf.wrapper');
        $pdfEstadoCuenta = $pdf->loadView('welcome', compact('referenciaPago', 'acumuladoSaldoVencido', 'ultimoPagoPDF', 'fechaProximoPago', 'logoDesarrollo', 'layoutEstadoCuenta', 'nombreCliente', 'vivienda', 'prototipo', 'torre', 'desarrollo', 'importePago', 'precioVivienda', 'acumuladoPagos'))
        ->save($this->path);
        // exit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Le recordamos que se acerca su fecha de corte')
        ->view('mail.cobranza', [
            'cliente' => $this->sendMailCobranza['cliente'],
            'torre' => $this->sendMailCobranza['torre'],
            'desarrollo' => $this->sendMailCobranza['desarrollo'],
            'fecha_pago' => $this->sendMailCobranza['fecha_pago'],
            'monto_pago' => $this->sendMailCobranza['monto_pago'],
            'referencia_pago' => $this->referencia
        /* ->markdown('mail.notificacion', [
            'cliente' => $this->sendMailCobranza['cliente'],
            'torre' => $this->sendMailCobranza['torre'],
            'desarrollo' => $this->sendMailCobranza['desarrollo'],
            'fecha_pago' => $this->sendMailCobranza['fecha_pago'],
            'monto_pago' => $this->sendMailCobranza['monto_pago'],
            'referencia_pago' => $this->referencia */
        ])->attach($this->path, [
            'as' => 'estado_de_cuenta_'.$this->sendMailCobranza['cliente'].'.pdf', 
            'mime' => 'application/pdf',
            ]);
    }
}

<?php

namespace App\Mail;

use App;
use App\SendMailCobranza;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Date\Date;

class NotificacionPagos extends Mailable
{
    use Queueable, SerializesModels;
    public $informacionClientesPlanCredito;
    public $rutaPDF;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($planCredito, $pathPDF)
    {
        // Array para almacenar la informacion de los clientes proximos a entregar
        $layoutReporteEntrega = [];
        $this->informacionClientesPlanCredito = $planCredito;
        $this->rutaPDF = $pathPDF;
        
        for ($i=1; $i < count($this->informacionClientesPlanCredito); $i++) {
            
            array_push($layoutReporteEntrega, [
                'cliente' => $this->informacionClientesPlanCredito[$i]['cliente'],
                'vivienda' => $this->informacionClientesPlanCredito[$i]['vivienda'],
                'prototipo' => $this->informacionClientesPlanCredito[$i]['prototipo'],
                'torre' => $this->informacionClientesPlanCredito[$i]['torre'],
                'desarrollo' => $this->informacionClientesPlanCredito[$i]['desarrollo'],
                'fecha_entrega' => $this->informacionClientesPlanCredito[$i]['fecha_entrega'],
                'monto_pago' => $this->informacionClientesPlanCredito[$i]['monto_pago'],
            ]);
        }
        // dd($layoutReporteEntrega); exit;
        // GENERACION DEL PDF
        $pdfEstadoCuenta = '';
        $pdf = App::make('dompdf.wrapper');
        $pdfEstadoCuenta = $pdf->loadView('email', compact('layoutReporteEntrega'))
        ->save($pathPDF);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Le recordamos que se acerca su fecha de corte')
        ->view('mail.notificacion', [
            'titulo' => 'Reporte proximas entregas',
            'destinatario' => 'Clemente Gonzalez'
        ])->attach($this->rutaPDF, [
            'as' => 'reporte_plan_credito.pdf', 
            'mime' => 'application/pdf',
        ]);
    }
}

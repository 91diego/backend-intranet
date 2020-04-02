<?php

namespace App\Http\Controllers\Cotizador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DisponibilidadCotizador;
use App\CrmEntity;
use App\Products;
use App\ProductsCf;


class CotizadorController extends Controller
{

    /**
     * Muestra disponibilidad que se tiene de departamentos por piso de manera general
     * @param string $desarrollo
     * @param string $numeroTorre
     */
    public function pisoGeneral($desarrollo, $numeroTorre) {
        $pisoGeneral = new DisponibilidadCotizador();
        $pisoGeneral = DB::table('vtiger_products')
        ->join('vtiger_crmentity', 'vtiger_products.productid', '=', 'vtiger_crmentity.crmid')
        ->join('vtiger_productcf', 'vtiger_productcf.productid', '=', 'vtiger_products.productid')
        //->where('vtiger_productcf.cf_1179', '=', 'Vivienda')
        ->select('vtiger_productcf.cf_1169 AS nivel', 'vtiger_productcf.cf_1165 AS tipo')
        ->selectRaw('COUNT(vtiger_products.productid) AS cant')
        ->selectRaw('SUM(vtiger_products.qtyinstock * vtiger_products.discontinued) AS disp')
        ->where('vtiger_productcf.cf_1179', '=', 'Vivienda')
        ->where('vtiger_productcf.cf_1163', '=', $desarrollo)
        ->where('vtiger_productcf.cf_1167', '=', $numeroTorre)
        ->where('vtiger_crmentity.deleted', '=', 0)
        ->groupBy('nivel', 'tipo')
        ->orderBy('nivel', 'DESC', 'tipo', 'ASC')
        ->get();
        echo json_encode($pisoGeneral);
    }

    /**
     * Muestra disponibilidad que se tiene en cada piso de la torre
     * @param string $desarrollo
     * @param string $numeroTorre
     */
    public function torre($desarrollo, $numeroTorre) {
        
        $disponibilidad = new DisponibilidadCotizador();
        $disponibilidad = DB::table('vtiger_products')
            ->join('vtiger_crmentity', 'vtiger_products.productid', '=', 'vtiger_crmentity.crmid')
            ->join('vtiger_productcf', 'vtiger_productcf.productid', '=', 'vtiger_products.productid')
            //->where('vtiger_productcf.cf_1179', '=', 'Vivienda')
            ->select('vtiger_productcf.cf_1167 AS torre', 'vtiger_productcf.cf_1165 AS tipo',
            'vtiger_productcf.cf_1169 AS piso')
            ->selectRaw('count(vtiger_products.productid) AS cant')
            ->selectRaw('sum(vtiger_products.qtyinstock * vtiger_products.discontinued) AS disp')
            ->where('vtiger_productcf.cf_1179', '=', 'Vivienda')
            ->where('vtiger_productcf.cf_1163', '=', $desarrollo)
            ->where('vtiger_productcf.cf_1167', '=', $numeroTorre)
            ->where('vtiger_crmentity.deleted', '=', 0)
            ->groupBy('torre', 'piso', 'tipo')
            ->orderBy('torre', 'DESC', 'piso', 'DESC', 'tipo', 'DESC')
            ->get();
            echo json_encode($disponibilidad);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $torres = Products::get();
        echo json_encode($torres);
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

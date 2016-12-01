<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-28 20:22:09
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-12-01 20:07:28
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../DB/db.php';
include_once '../Model/Companhia.php';
include_once '../Model/Voo.php';

use DB\DB;
use Model\Companhia;
use Model\Voo;

$method  = $_GET;
$inicio  = inverte( $method['data_inicio'] );
$fim 		 = inverte( $method['data_fim'] );
$last    = -1;
$tmp     = '';
$comp    = ( isset( $method['companhia'] ) ) ? trim( $method['companhia'] ): '';

if( $_GET['tipo'] == '1' )
	echo json_encode( ['data' => getPaisSemana( $inicio, $fim )] );
else if( $_GET['tipo'] == '2' )
	echo json_encode( ['data' => getVoosCompanhia( $inicio, $fim, $comp )] );
else if( $_GET['tipo'] == '3' )
	echo json_encode( ['data' => getCancelados( $inicio, $fim )] );
else if( $_GET['tipo'] == '4' )
	echo json_encode( ['data' => ranking( $inicio, $fim )] );

function getCancelados( $inicio, $fim ) {
	$arr = [];
	$res = Companhia::buscarComCancelados( $inicio, $fim );

	foreach( $res as $r )
		$arr[] = [ 'contagem'=>$r['contagem'], 'codigo'=>$r['companhia']->getCodigo(), 'nome'=>$r['companhia']->getNome(), 'sigla'=>$r['companhia']->getSigla() ];

	return [ 'data'=>$arr ];
}

function getVoosCompanhia( $inicio, $fim, $companhia ) {
	$c = new Companhia( ['codigo'=>$companhia ] );
	return $c->obterContagemVoos( $inicio, $fim );
}

function getPaisSemana( $inicio, $fim ) {
	return Voo::obterNumVoosPorPais( $inicio, $fim );
}

function ranking( $inicio, $fim ) {
	$arr = [];
	$res = Voo::obterRanking( $inicio, $fim );
	foreach( $res as $r )
		$arr[] = [ 'contagem'=>$r['contagem'], 'codigo'=>$r['voo']->getCodigo(), 'origem'=>$r['voo']->getOrigem()->getNome().', '.$r['voo']->getOrigem()->getCidade().'-'.$r['voo']->getOrigem()->getEstado().' ('.$r['voo']->getOrigem()->getPais().')', 'destino'=>$r['voo']->getDestino()->getNome().', '.$r['voo']->getDestino()->getCidade().'-'.$r['voo']->getDestino()->getEstado().' ('.$r['voo']->getDestino()->getPais().')', 'codigo'=>$r['voo']->getCodigo() ];

	return [ 'data'=>$arr ];
}

function inverte( $data ) {
	return $data[4].$data[5].$data[6].$data[7].'-'.$data[2].$data[3].'-'.$data[0].$data[1];
}
<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-25 01:06:41
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-30 11:32:19
 */

include_once '../Model/Voo.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Companhia.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Horario.php';
include_once '../Model/Escala.php';
include_once '../DB/db.php';
include_once '../vendor/autoload.php';

use DB\DB;
use Model\Voo;
use Model\Horario;
use Model\Aeroporto;
use Model\Aeronave;
use Model\Companhia;
use Model\Escala;
use Carbon\Carbon;

$method = $_POST;

$aeronave  = Aeronave::buscar( "modelo='{$method[ 'modelo_aeronave' ]}'" )[0];
$origem    = Aeroporto::buscar( 'codigo='.$method['cod_origem'] )[0];
$destino   = Aeroporto::buscar( 'codigo='.$method['cod_destino'] )[0];
$saida     = new Horario( [ 'hora' => str_replace(':', '', $method['hora_partida'].'00'), 'data' => inverte( str_replace('/', '', $method['data_partida'] ) ) ] );
$chegada   = new Horario( [ 'hora' => str_replace(':', '', $method['hora_chegada'].'00'), 'data' => inverte( str_replace('/', '', $method['data_chegada'] ) ) ] );
$companhia = Companhia::buscar( 'codigo='.$method['companhia'] )[0];
$escalas   = $method['escalas'];
$json      = [];
$arr 			 = [ 'aeronave'=>$aeronave, 'companhia'=>$companhia, 'origem'=>$origem, 'destino'=>$destino, 'saida'=>$saida, 'chegada'=>$chegada ];
$errors    = [];
$data_p    = null;
$data_c    = null;

if( isset($method['edit']) ) {
	$arr['codigo'] = $method['edit'];
	$arr['status'] = $method['status'];
} else
	$arr['status'] = 'ativo';

$now    = new Carbon();
$data_p = Carbon::createFromFormat( 'Y-m-d H:i:s', formatDate($saida->getData()).' '.formatHour($saida->getHora()) );
$data_c = Carbon::createFromFormat( 'Y-m-d H:i:s', formatDate($chegada->getData()).' '.formatHour($chegada->getHora()) );

if ( !validateDate( formatDate($saida->getData()).' '.formatHour($saida->getHora()) ) )
	$errors[] = 'Data de partida no formato inválido';
if ( !validateDate( formatDate($chegada->getData()).' '.formatHour($chegada->getHora()) ) )
	$errors[] = 'Data de chegada no formato inválido';

if( count($errors) ){
	echo json_encode( [ 'erro' => $errors ] );
	return false;
} else if( $now->gte( $data_p ) && $arr['status']!='finalizado' ) {
	echo json_encode( [ 'erro' => ['Data informada anterior a data atual'] ] );
	return false;
} else if( $data_c->lte( $data_p ) ){
	echo json_encode( [ 'erro' => ['Diferença entre data de partida e chegada inválida :('] ] );
	return false;
}

$repete = isset( $method['qtd_replica'] ) && $method['qtd_replica'] != '' ? intval( $method[ 'qtd_replica' ] )+1 : 1;
$period = null;

if ( isset( $method['periodo_replica'] ) && $method['periodo_replica'] == '1' )
	$period = 'addDay';
else if ( isset( $method['periodo_replica'] ) && $method['periodo_replica'] == '2' )
	$period = 'addWeek';
else if ( isset( $method['periodo_replica'] ) && $method['periodo_replica'] == '3' )
	$period = 'addMonth';

$voo = new Voo( $arr );

for( $i=1; $i<=$repete; $i++ ) {

	if( !( isset($arr['codigo']) && $i==1 ) )
		$voo->setCodigo( null );
	$json[] = $voo->gravar();

	DB::clearTable('Escala', "where cod_voo={$voo->getCodigo()}");

	foreach( $escalas as $es ) {
		$e = $es[ 'el' ];
		$aeroporto = new Aeroporto( [ 'codigo'=>$e['codigo'], 'nome'=>$e['nome'], 'cidade'=>$e['cidade'], 'estado'=>$e['estado'], 'pais'=>$e['pais'] ] );
		//Escala
		$escala = new Escala( [ 'voo'=>$voo, 'aeroporto'=>$aeroporto ] );
		$escala->gravar();
	}

	$data_p = Carbon::createFromFormat( 'Y-m-d H:i:s', formatDate($voo->getPartida()->getData()).' '.formatHour($voo->getPartida()->getHora()) );
	$data_c = Carbon::createFromFormat( 'Y-m-d H:i:s', formatDate($voo->getChegada()->getData()).' '.formatHour($voo->getChegada()->getHora()) );
	if( $period ) {
		$data_p = $data_p->$period();
		$data_c = $data_c->$period();
	}

	$partida = [ 'data'=>$data_p->format('Ymd'), 'hora'=>$data_p->format('His') ];
	$chegada = [ 'data'=>$data_c->format('Ymd'), 'hora'=>$data_c->format('His') ];
	$voo->setPartida( new Horario( $partida ) )->setChegada( new Horario( $chegada ) );
}

if( isset( $method['edit'] ) )
	echo json_encode( ['mensagem'=>"Você editou as informações do voo #{$arr['codigo']} :)", 'data'=> $arr ] );
else
	echo json_encode( ['mensagem'=>"Você cadastrou um voo :)", 'data'=>$json] );

function validateDate($date, $format = 'Y-m-d H:i:s') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}

function formatDate( $date ) {
	$str = '';
	for( $i=0; $i<8; $i++ )
		$str .= ( $i==4||$i==6 ) ? '-'.$date[ $i ] : $date[ $i ];
	return $str;
}
function formatHour( $hour ) {
	$str = '';
	for( $i=0; $i<6; $i++ )
		$str .= ( $i==2||$i==4 ) ? ':'.$hour[ $i ] : $hour[ $i ];
	return $str;
}

function inverte( $data ) {
	return $data[4].$data[5].$data[6].$data[7].$data[2].$data[3].$data[0].$data[1];
}
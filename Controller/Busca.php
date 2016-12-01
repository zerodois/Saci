<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-27 11:03:11
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-12-01 17:21:27
 */
include_once '../Model/Voo.php';
include_once '../Model/Escala.php';
include_once '../Model/Horario.php';
include_once '../vendor/autoload.php';

use Carbon\Carbon;
use Model\Horario;
use Model\Escala;
use Model\Voo;
use DB\DB;

$errors = [];
$modelo  = verify( 'codigo', "='%s'", $modelo );
$modelo .= verify( 'origem', "='%s'", $modelo, 'cod_origem' );
$modelo .= verify( 'destino', "='%s'", $modelo, 'cod_destino' );
$modelo .= verify( 'companhia', "='%s'", $modelo, 'cod_companhia' );
$modelo .= verify( 'data_partida', "='%s'", $modelo );
$modelo .= verify( 'data_chegada', "='%s'", $modelo );
$modelo .= verify( 'data_inicio', ">='%s'", $modelo, 'data_partida', true );
$modelo .= verify( 'data_fim', "<='%s'", $modelo, 'data_partida', true );


if( isset($_GET['data_inicio']) && !validateDate(trim($_GET['data_inicio']), 'dmY') )
	$error[] = 'A data inicial informada é uma data inválida';
if( isset($_GET['data_fim']) && !validateDate(trim($_GET['data_fim']), 'dmY') )
	$error[] = 'A data final informada '.(count($error)?'também':'').'é uma data inválida';

if( count($error) ) {
	echo json_encode( [ 'error'=>$error ] );
	return false;
}

if( isset( $_GET['escalas'] ) ) {
	$arr  = $_GET['escalas'];
	$size = count( $arr );

	$modelo = ( $modelo == '' )?"Escala.cod_aeroporto = '{$arr[ $i ]}'":"and Escala.cod_aeroporto = '{$arr[ $i ]}'";
	for( $i=1; $i<$size; $i++ )
		$modelo .= "and Escala.cod_aeroporto = '{$arr[ $i ]}'";
	$modelo .= "Inner join Escala on Escala.cod_voo=Voo.codigo";
}

$all = Voo::buscar( $modelo );

foreach( $all as $item ) {

	$origem = [ 'codigo'=>$item->getOrigem()->getCodigo(), 'nome'=>$item->getOrigem()->getNome(), 'cidade'=>$item->getOrigem()->getCidade(), 'estado'=>$item->getOrigem()->getEstado(), 'pais'=>$item->getOrigem()->getPais() ];
	$destino = [ 'codigo'=>$item->getDestino()->getCodigo(), 'nome'=>$item->getDestino()->getNome(), 'cidade'=>$item->getDestino()->getCidade(), 'estado'=>$item->getDestino()->getEstado(), 'pais'=>$item->getDestino()->getPais() ];
	$companhia = [ 'codigo' => $item->getCompanhia()->getCodigo(), 'nome' => $item->getCompanhia()->getNome(), 'sigla' => $item->getCompanhia()->getSigla() ];
	$aeronave = [ 'modelo' => $item->getAeronave()->getModelo() ];

	$es = Escala::buscar( "cod_voo='{$item->getCodigo()}'" );
	$escalas = [];

	foreach( $es as $e )
		$escalas[] = [ 'codigo'=>$e->getAeroporto()->getCodigo(), 'nome'=>$e->getAeroporto()->getNome(), 'cidade'=>$e->getAeroporto()->getCidade(), 'estado'=>$e->getAeroporto()->getEstado(), 'pais'=>$e->getAeroporto()->getPais() ];

	$arr[] = [
		'o' => $origem,
		'd' => $destino,
		'a' => $aeronave,
		'escalas' => $escalas,
		'companhia' => $companhia, 
		'codigo' => $item->getCodigo(),
		'status' =>$item->getStatus(),
		'origem' => $item->getOrigem()->getNome()." ({$item->getOrigem()->getCidade()}/{$item->getOrigem()->getEstado()})",
		'destino' => $item->getDestino()->getNome()." ({$item->getDestino()->getCidade()}/{$item->getDestino()->getEstado()})",
		'aeronave' => $item->getAeronave()->getModelo(),
		'data_partida' => DB::formatDate( $item->getPartida()->getData() ),
		'hora_partida' => DB::formatHour( $item->getPartida()->getHora() ),
		'data_chegada' => DB::formatDate( $item->getChegada()->getData() ),
		'hora_chegada' => DB::formatHour( $item->getChegada()->getHora() ),			
		'partida' => DB::formatDate( $item->getPartida()->getData() ).' '.DB::formatHour( $item->getPartida()->getHora() ),
		'chegada' => DB::formatDate( $item->getChegada()->getData() ).' '.DB::formatHour( $item->getChegada()->getHora() )
	];
}

echo json_encode( [ 'size' => count($all), 'data'=>$arr ] );

function verify( $index, $text, $str, $field='', $isDate = false ) {
	if( $field=='' ) $field = $index;
	if( !( isset( $_GET[ $index ] ) && trim( $_GET[ $index ] )!='' ) )
		return '';
	$value = trim( $_GET[ $index ] );
	if( $isDate )
		$value = inverte( $value );
	return str_replace( '%s', $value, ( $str!=''?' and ':'' )."{$field} {$text}" );
}

function inverte( $data ) {
	return $data[4].$data[5].$data[6].$data[7].$data[2].$data[3].$data[0].$data[1];
}

function validateDate($date, $format = 'Y-m-d H:i:s') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}
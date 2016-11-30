<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-27 11:03:11
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-29 20:59:12
 */
include_once '../Model/Voo.php';
include_once '../Model/Escala.php';
include_once '../Model/Horario.php';

use Model\Horario;
use Model\Escala;
use Model\Voo;
use DB\DB;

$modelo  = verify( 'codigo', "='%s'", $modelo );
$modelo .= verify( 'origem', "='%s'", $modelo, 'cod_origem' );
$modelo .= verify( 'destino', "='%s'", $modelo, 'cod_destino' );
$modelo .= verify( 'companhia', "='%s'", $modelo, 'cod_companhia' );
$modelo .= verify( 'data_partida', "='%s'", $modelo );
$modelo .= verify( 'data_chegada', "='%s'", $modelo );

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

function verify( $index, $text, $str, $field='' ) {
	if( $field=='' ) $field = $index;
	if( isset( $_GET[ $index ] ) && trim( $_GET[ $index ] )!='' )
		return str_replace( '%s', trim($_GET[ $index ]), ( $str!=''?' and ':'' )."{$field} {$text}" );
	return '';
}
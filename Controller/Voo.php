<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-25 01:06:41
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-25 02:59:51
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Model/Voo.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Companhia.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Horario.php';
include_once '../Model/Escala.php';
use Model\Voo;
use Model\Horario;
use Model\Aeroporto;
use Model\Aeronave;
use Model\Companhia;
use Model\Escala;

$method = $_POST;

$aeronave  = Aeronave::buscar( "modelo='{$method[ 'modelo_aeronave' ]}'" )[0];
$origem    = Aeroporto::buscar( 'codigo='.$method['cod_origem'] )[0];
$destino   = Aeroporto::buscar( 'codigo='.$method['cod_destino'] )[0];
$saida     = new Horario( [ 'hora' => $method['hora_partida'].'00', 'data' => inverte( $method['data_partida'] ) ] );
$chegada   = new Horario( [ 'hora' => $method['hora_chegada'].'00', 'data' => inverte( $method['data_chegada'] ) ] );
$companhia = Companhia::buscar( 'codigo='.$method['companhia'] )[0];
$escalas   = $method['escalas'];

$arr = [ 'status'=>'confirmado', 'aeronave'=>$aeronave, 'companhia'=>$companhia, 'origem'=>$origem, 'destino'=>$destino, 'saida'=>$saida, 'chegada'=>$chegada ];

$voo = new Voo( $arr );
$voo->gravar();

foreach( $escalas as $es ) {
	$e = $es[ 'el' ];
	$aeroporto = new Aeroporto( [ 'codigo'=>$e['codigo'], 'nome'=>$e['nome'], 'cidade'=>$e['cidade'], 'estado'=>$e['estado'], 'pais'=>$e['pais'] ] );
	//Escala
	$escala = new Escala( [ 'voo'=>$voo, 'aeroporto'=>$aeroporto ] );
	$escala->gravar();
}

echo json_encode( ['mensagem'=>'Dados gravados com sucesso'] );

function inverte( $data ) {
	return $data[4].$data[5].$data[6].$data[7].$data[2].$data[3].$data[0].$data[1];
}


//echo json_encode( $voo->save() );
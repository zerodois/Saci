<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-25 01:06:41
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-25 02:18:01
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Model/Voo.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Companhia.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Horario.php';
use Model\Voo;
use Model\Horario;
use Model\Aeroporto;
use Model\Aeronave;
use Model\Companhia;

$method = $_POST;

$aeronave = Aeronave::buscar( "modelo='{$method[ 'modelo_aeronave' ]}'" )[0];
$origem   = Aeroporto::buscar( 'codigo='.$method['cod_origem'] )[0];
$destino  = Aeroporto::buscar( 'codigo='.$method['cod_destino'] )[0];
$saida    = new Horario( [ 'hora' => $method['hora_partida'], 'data' => $method['data_partida'] ] );
$chegada  = new Horario( [ 'hora' => $method['hora_chegada'], 'data' => $method['data_chegada'] ] );
$companhia = Companhia::buscar( 'codigo='.$method['companhia'] );

$arr = [ 'status'=>'confirmado', 'aeronave'=>$aeronave, 'companhia'=>$companhia, 'origem'=>$origem, 'destino'=>$destino, 'saida'=>$saida, 'chegada'=>$chegada ];

echo json_encode( $arr );

//$voo = new Voo( $arr );

//echo json_encode( $voo->save() );
<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-24 15:32:28
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-24 23:49:42
 */

include_once '../Model/Aeronave.php';
use Model\Aeronave;


$modelo = isset( $_GET['modelo'] ) ? "modelo like '%".trim( $_GET['modelo'] )."%'" : '';
$all = Aeronave::buscar( $modelo );
$arr = [];

foreach( $all as $item )
	$arr[] = [ 'modelo' => $item->getModelo(), 'tripulacao' => $item->getQtdTripulacao(), 'passageiros' => $item->getQtdpassageiro(), 'capacidade' => $item->getQtdTripulacao()+$item->getQtdpassageiro() ];

echo json_encode( [ 'data'=>$arr ] );
<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 15:26:33
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-22 19:43:01
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../DB/db.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Companhia.php';
include_once '../Model/Horario.php';
include_once '../Model/Voo.php';
include_once '../Model/Escala.php';

use Model\Aeroporto;
use Model\Aeronave;
use Model\Companhia;
use Model\horario;
use Model\Escala;
use Model\Voo;
use DB\DB;

var_dump( Voo::obterRanking('2016-11-20', '2016-11-26') );

die();
$arr = Escala::buscar();

foreach( $arr as $a ) {
	echo "Aeroporto : {$a->getAeroporto()->getNome()} - Voo : {$a->getVoo()->getCodigo()}<br>";
}
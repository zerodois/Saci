<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-27 14:21:04
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-27 17:14:26
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Model/Voo.php';
use Model\Voo;

$method = $_POST;
$voo = Voo::buscar( "codigo='{$method[ 'codigo' ]}'" )[0];
$voo->setStatus( $method['status'] );

echo json_encode( $voo->gravar() ); 
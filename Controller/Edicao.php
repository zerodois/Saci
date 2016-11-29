<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-27 14:21:04
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-28 21:00:31
 */

include_once '../Model/Voo.php';
use Model\Voo;

$method = $_POST;
$voo = Voo::buscar( "codigo='{$method[ 'codigo' ]}'" )[0];
$voo->setStatus( $method['status'] );

echo json_encode( $voo->gravar() ); 
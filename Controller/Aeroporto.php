<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-24 18:46:43
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-24 19:35:58
 */

include_once '../Model/Aeroporto.php';
use Model\Aeroporto;

$modelo  = verify( 'codigo', "='%s'", $modelo );
$modelo .= verify( 'nome', "like '%%s%'", $modelo );
$modelo .= verify( 'cidade', "like '%%s%'", $modelo );

$all = Aeroporto::buscar( $modelo );

foreach( $all as $item )
	$arr[] = [ 'codigo' => $item->getCodigo(), 'nome' => $item->getNome(), 'cidade' => $item->getCidade(), 'estado' => $item->getEstado(), 'pais'=>$item->getPais() ];

echo json_encode( [ 'size' => $modelo, 'data'=>$arr ] );

function verify( $index, $text, $str ) {
	if( isset( $_GET[ $index ] ) && trim( $_GET[ $index ] )!='' )
		return str_replace( '%s', trim($_GET[ $index ]), ( $str!=''?' and ':'' )."{$index} {$text}" );
	return '';
}
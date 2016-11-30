<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-24 23:26:14
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-24 23:27:33
 */

include_once '../Model/Companhia.php';
use Model\Companhia;

$modelo  = verify( 'codigo', "='%s'", $modelo );
$modelo .= verify( 'nome', "like '%%s%'", $modelo );
$modelo .= verify( 'sigla', "like '%s%'", $modelo );

$all = Companhia::buscar( $modelo );

foreach( $all as $item )
	$arr[] = [ 'codigo' => $item->getCodigo(), 'nome' => $item->getNome(), 'sigla' => $item->getSigla() ];

echo json_encode( [ 'size' => $modelo, 'data'=>$arr ] );

function verify( $index, $text, $str ) {
	if( isset( $_GET[ $index ] ) && trim( $_GET[ $index ] )!='' )
		return str_replace( '%s', trim($_GET[ $index ]), ( $str!=''?' and ':'' )."{$index} {$text}" );
	return '';
}
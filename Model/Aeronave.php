<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 17:03:47
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-25 01:44:14
 */

namespace Model;
include_once '../DB/db.php';
   
use DB\DB;


class Aeronave {

	private $modelo;
	private $qtd_tripulacao;
	private $qtd_passageiro;

	public function __construct( $data ) {
		$this->modelo 				= array_key_exists( 'modelo', $data ) ? $data[ 'modelo' ] : null;
		$this->qtd_tripulacao = array_key_exists( 'qtd_tripulacao', $data ) ? $data['qtd_tripulacao'] : null;
		$this->qtd_passageiro = array_key_exists( 'qtd_passageiro', $data ) ? $data['qtd_passageiro'] : null;
	}

  public function getModelo() {
    return $this->modelo;
  }

  public function getQtdTripulacao() {
    return $this->qtd_tripulacao;
  }

  public function getQtdpassageiro() {
    return $this->qtd_passageiro;
  }

  public static function buscar( $filtro='' ) {

  	$sql = 'select * from ModeloAeronave %s';
  	$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( $sql, $where ) );
		$arr   = [];

		foreach( $vetor as $item )
			$arr[] = new Aeronave( $item );

		return $arr;
  }
}
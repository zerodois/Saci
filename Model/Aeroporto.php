<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 16:19:05
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-22 19:48:41
 */

namespace Model;
include_once '../DB/db.php';
use DB\DB;

class Aeroporto {
	
	private $codigo;
	private $nome;
	private $cidade;
	private $estado;
	private $pais;

	public function __construct( $data ) {
		$this->codigo = array_key_exists( 'codigo', $data ) ? $data[ 'codigo' ] : null;
		$this->nome 	= array_key_exists( 'nome', $data ) ? $data['nome'] : null;
		$this->cidade = array_key_exists( 'cidade', $data ) ? $data['cidade'] : null;
		$this->estado = array_key_exists( 'estado', $data ) ? $data['estado'] : null;
		$this->pais 	= array_key_exists( 'pais', $data ) ? $data['pais'] : null;
	}

	public function getCodigo() {
	  return $this->codigo;
	}
	public function setCodigo($codigo) {
	  $this->codigo = $codigo;
	  return $this;
	}

	public function getNome() {
	  return $this->nome;
	}

	public function getCidade() {
	  return $this->cidade;
	}

	public function getEstado() {
	  return $this->estado;
	}

	public function getPais() {
	  return $this->pais;
	}

	public static function buscar( $filtro = '' ) {

		$sql = 'select * from Aeroporto %s';
		$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( $sql, $where ) );
		$arr   = [];

		foreach( $vetor as $item )
			$arr[] = new Aeroporto( $item );

		return $arr;
	}

}

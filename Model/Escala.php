<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-22 18:58:31
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-12-01 20:35:36
 */

namespace Model;
include_once '../DB/db.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Voo.php';
use DB\DB;

class Escala {

	private $voo;
	private $aeroporto;

	private static $sqlBusca = "select Escala.*, Aeroporto.* from Escala inner join Aeroporto on Escala.cod_aeroporto=Aeroporto.codigo %s";

	public function __construct( $data ) {
		$this->voo 			 = array_key_exists( 'voo', $data ) ? $data['voo'] : null;
		$this->aeroporto = array_key_exists( 'aeroporto', $data ) ? $data['aeroporto'] : null;
		return $this;
	}

	public function setVoo( $voo ) {
		$this->voo = $voo;
		return $this;
	}
	public function getVoo() {
		return $this->voo;
	}

	public function setAeroporto( $aeroporto ) {
		$this->aeroporto = $aeroporto;
		return $this;
	}
	public function getAeroporto() {
		return $this->aeroporto;
	}

	public function gravar() {
		$sqlInsert = "insert into Escala values('%s', '%s')";
		return DB::executarComando( sprintf( $sqlInsert, $this->voo->getCodigo(), $this->aeroporto->getCodigo() ) );
	}

	public function remover() {
		$sqlDelete = "delete from Escala where cod_voo='%s' and cod_aeroporto='%s'";
		return DB::executarComando( sprintf( $this->sqlDelete, $this->voo->getCodigo, $this->aeroporto->getCodigo ) );
	}

	public static function buscar( $filtro='' ) {

		$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( self::$sqlBusca, $where ) );
		$arr   = [];

		foreach( $vetor as $item ) {
			$aeroporto = new Aeroporto( $item );
			$voo       = new Voo( ['aeroporto'=>$aeroporto] );
			$arr[] = new Escala( [ 'voo'=>$voo, 'aeroporto'=>Aeroporto::buscar("codigo={$item['cod_aeroporto']}")[0] ] );
		}

		return $arr;
	}

}

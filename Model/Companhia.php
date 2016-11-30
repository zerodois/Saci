<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 17:21:08
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-29 01:04:16
 */

namespace Model;
include_once '../DB/db.php';
use DB\DB;

class Companhia {

	private $codigo;
	private $sigla;
	private $nome;

	public function __construct( $data ) {
		$this->codigo = array_key_exists( 'codigo', $data ) ? $data['codigo'] : null;
		$this->sigla 	= array_key_exists( 'sigla', $data ) ? $data['sigla'] : null;
		$this->nome 	= array_key_exists( 'nome', $data ) ? $data['nome'] : null;
	}

  public function getCodigo() {
    return $this->codigo;
  }

  public function getSigla() {
    return $this->sigla;
  }

  public function getNome() {
    return $this->nome;
  }

  public static function buscar( $filtro='' ) {

  	$sql = 'select * from Companhia %s';
		$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( $sql, $where ) );
		$arr   = [];

		foreach( $vetor as $item )
			$arr[] = new Companhia( $item );

		return $arr;
  }

  public function obterContagemVoos($dataInicio, $dataFinal) {
  	$sql = "select count(*) as total , count(if(status='ativo',1,null)) as ativos, count(if(status='confirmado',1,null)) as confirmados, count(if(status='finalizado',1,null)) as finalizados, count(if(status='cancelado',1,null)) as cancelados from Voo where cod_companhia = '{$this->codigo}' and data_partida >= '{$dataInicio}' and data_chegada <= '{$dataFinal}'";
  	
		return DB::executarConsulta( $sql )[0];
  }

  public static function buscarComCancelados( $dataInicio, $dataFinal ) {
  	$sqlInner = "select Companhia.*, count(if(status='cancelado',1,null)) as contagem from Companhia join Voo on Voo.cod_companhia = Companhia.codigo where data_partida >= '{$dataInicio}' and data_chegada <= '{$dataFinal}' group by codigo order by contagem desc, nome";

		$vetor = DB::executarConsulta( $sqlInner );
		$arr   = [];

		foreach( $vetor as $item )
			$arr[] = [ 'contagem' => $item['contagem'], 'companhia' => new Companhia( $item ) ];

		return $arr;
  }

}

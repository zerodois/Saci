<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 20:11:11
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-22 19:47:22
 */

namespace Model;
include_once '../DB/db.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Horario.php';
include_once '../Model/Companhia.php';

use DB\DB;

class Voo {

	private $status;
	private $codigo;
	private $origem;  //Aeroporto
	private $destino; //Aeroporto
	private $saida; //Horario
	private $chegada; //Horario
	private $companhia; //Companhia
	private $aeronave; //Aeronave

	public function __construct( $data ) {
		$this->status 	 = array_key_exists( 'status', $data ) ? $data['status'] : null;
		$this->codigo 	 = array_key_exists( 'codigo', $data ) ? $data['codigo'] : null;
		$this->origem 	 = array_key_exists( 'origem', $data ) ? $data['origem'] : new Aeroporto();
		$this->destino 	 = array_key_exists( 'destino', $data ) ? $data['destino'] : new Aeroporto();
		$this->saida 		 = array_key_exists( 'saida', $data ) ? $data['saida'] : new Horario();
		$this->chegada 	 = array_key_exists( 'chegada', $data ) ? $data['chegada'] : new Horario();
		$this->companhia = array_key_exists( 'companhia', $data ) ? $data['companhia'] : new Companhia();
		$this->aeronave  = array_key_exists( 'aeronave', $data ) ? $data['aeronave'] : new Aeronave();
	}

	public function getStatus() {
    return $this->status;
  }
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  public function getCodigo() {
    return $this->codigo;
  }
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
    return $this;
  }

  public function gravar() {

  	$sqlInsert  = "insert into Voo values (null,'%s','%s','%s','%s','%s','%s','%s','%s','%s')";
  	$sqlUpdate  = "update Voo set status='%s', data_partida='%s', hora_partida='%s', data_chegada='%s', hora_chegada='%s', modelo_aeronave='%s', cod_companhia='%s', cod_origem='%s', cod_destino='%s' where codigo='%s' ";

  	$update = sprintf( $sqlUpdate, $this->getStatus(), $this->saida->getData(), $this->saida->getHora(), $this->chegada->getData(), $this->chegada->getHora(), $this->aeronave->getModelo(), $this->companhia->getCodigo(), $this->origem->getCodigo(), $this->destino->getCodigo(), $this->codigo );

  	$insert = sprintf( $sqlInsert, $this->getStatus(), $this->saida->getData(), $this->saida->getHora(), $this->chegada->getData(), $this->chegada->getHora(), $this->aeronave->getModelo(), $this->companhia->getCodigo(), $this->origem->getCodigo(), $this->destino->getCodigo() );

  	$this->saida->gravar();
		$this->chegada->gravar();

  	if( $this->codigo )
  		return DB::executarComando( $update );
  	
  	$arr = DB::executarComando( $insert );
  	$this->codigo = $arr['insert_id'];
  	return $this;
  }

  public static function buscar( $filtro='' ) {

  	$sqlBusca   = "select * from Voo %s";
		$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( $sqlBusca, $where ) );
		$arr   = [];

		foreach ( $vetor as $el ) {

			$el[ 'origem' ]    = Aeroporto::buscar( "codigo={$el['cod_origem']}" )[0];
			$el[ 'destino' ]   = Aeroporto::buscar( "codigo={$el['cod_destino']}" )[0];
			$el[ 'saida' ]     = new Horario( [ 'hora'=> $el['hora_partida'], 'data'=>$el['data_partida'] ] );
			$el[ 'chegada' ]   = new Horario( [ 'hora'=> $el['hora_chegada'], 'data'=>$el['data_chegada'] ] );
			$el[ 'companhia' ] = Companhia::buscar( "codigo={$el['cod_companhia']}" )[0];
			$el[ 'aeronave' ]  = Aeronave::buscar( "modelo='{$el['modelo_aeronave']}'" )[0];
			
			$arr[] = new Voo( $el );
		}

		return $arr;
  }

  public static function obterRanking( $dataInicio, $dataFinal ) {
		
		$sqlRanking = "select Voo.*, count(Escala.cod_voo) as contagem from Voo left join Escala on Escala.cod_voo=Voo.codigo where Voo.data_partida>='%s' and Voo.data_chegada<='%s' group by Voo.codigo order by contagem desc";

		$vetor = DB::executarConsulta( sprintf( $sqlRanking, $dataInicio, $dataFinal ) );
		$arr   = [];

		foreach ( $vetor as $el ) {

			$el[ 'origem' ]    = Aeroporto::buscar( "codigo={$el['cod_origem']}" )[0];
			$el[ 'destino' ]   = Aeroporto::buscar( "codigo={$el['cod_destino']}" )[0];
			$el[ 'saida' ]     = new Horario( [ 'hora'=> $el['hora_partida'], 'data'=>$el['data_partida'] ] );
			$el[ 'chegada' ]   = new Horario( [ 'hora'=> $el['hora_chegada'], 'data'=>$el['data_chegada'] ] );
			$el[ 'companhia' ] = Companhia::buscar( "codigo={$el['cod_companhia']}" )[0];
			$el[ 'aeronave' ]  = Aeronave::buscar( "modelo='{$el['modelo_aeronave']}'" )[0];
			
			$arr[] = [ 'contagem' => $el['contagem'], 'voo' => new Voo( $el ) ];
		}

		return $arr;
  }

  public static function obterNumVoosPorPais( $dataInicio, $dataFinal ) {

  	$sqlVoos    = "select Aeroporto.pais, count(Voo.codigo) as contagem from Voo right join Aeroporto on Voo.cod_destino = Aeroporto.codigo where Voo.data_partida>='%s' and Voo.data_chegada<='%s' group by Aeroporto.pais";

  	return DB::executarConsulta( sprintf( $sqlVoos, $dataInicio, $dataFinal ) );
  }

}
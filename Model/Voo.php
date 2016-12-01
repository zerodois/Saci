<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 20:11:11
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-12-01 20:20:39
 */

namespace Model;
include_once '../DB/db.php';
include_once '../Model/Aeronave.php';
include_once '../Model/Aeroporto.php';
include_once '../Model/Horario.php';
include_once '../Model/Companhia.php';
include_once '../vendor/autoload.php';

use DB\DB;
use Model\Horario;
use Model\Aeroporto;
use Model\Aeronave;
use Model\Companhia;
use Carbon\Carbon;

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

	public function getPartida() {
		return $this->saida;
	}
	public function setPartida( $partida ) {
		$this->saida = $partida;
		return $this;
	}
	public function getChegada() {
		return $this->chegada;
	}
	public function setChegada( $chegada ) {
		$this->chegada = $chegada;
		return $this;
	}
	public function getStatus() {
    return $this->status;
  }
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }
  public function getOrigem() {
  	return $this->origem;
  }
  public function getAeronave() {
  	return $this->aeronave;
  }
  public function getCompanhia() {
  	return $this->companhia;
  }
  public function getDestino() {
  	return $this->destino;
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

  	$sqlBusca   = "SELECT Voo.*,sigla, Companhia.codigo AS codigo_companhia, Companhia.nome AS nome_companhia, qtd_tripulacao,qtd_passageiro, Origem.codigo AS codigo_origem, Origem.nome AS nome_origem,Origem.pais AS pais_origem, Origem.estado AS estado_origem, Origem.cidade AS cidade_origem, Destino.codigo AS codigo_destino, Destino.nome AS nome_destino, Destino.pais AS pais_destino,Destino.estado AS estado_destino,Destino.cidade AS cidade_destino FROM Voo JOIN Companhia ON Voo.cod_companhia = Companhia.codigo JOIN ModeloAeronave ON Voo.modelo_aeronave = ModeloAeronave.modelo JOIN Aeroporto AS Origem ON Voo.cod_origem = Origem.codigo JOIN Aeroporto AS Destino ON Voo.cod_destino = Destino.codigo %s";
		$where = $filtro;
		if( $filtro != '' )
			$where = sprintf( 'where %s', $filtro );

		$vetor = DB::executarConsulta( sprintf( $sqlBusca, $where ) );
		$arr   = [];

		foreach ( $vetor as $el ) {

			$el[ 'origem' ]    = new Aeroporto([ 'codigo'=>$el['codigo_origem'], 'nome'=>$el['nome_origem'], 'cidade'=>$el['cidade_origem'], 'estado'=>$el['estado_origem'], 'pais'=>$el['pais_origem'] ]);
			$el[ 'destino' ]    = new Aeroporto([ 'codigo'=>$el['codigo_destino'], 'nome'=>$el['nome_destino'], 'cidade'=>$el['cidade_destino'], 'estado'=>$el['estado_destino'], 'pais'=>$el['pais_destino'] ]);
			$el[ 'saida' ]     = new Horario( [ 'hora'=> $el['hora_partida'], 'data'=>$el['data_partida'] ] );
			$el[ 'chegada' ]   = new Horario( [ 'hora'=> $el['hora_chegada'], 'data'=>$el['data_chegada'] ] );
			$el[ 'companhia' ] = new Companhia([ 'nome'=>$el['nome_companhia'], 'codigo'=>$el['codigo_companhia'], 'sigla'=>$el['sigla'] ] );
			$el[ 'aeronave' ]  = new Aeronave([ 'qtd_tripulacao'=>$el['qtd_tripulacao'], 'qtd_passageiro'=>$el['qtd_passageiro'], 'modelo'=>$el['modelo_aeronave'] ]);//Aeronave::buscar( "modelo='{$el['modelo_aeronave']}'" )[0];
			
			$arr[] = new Voo( $el );
		}

		return $arr;
  }

  public static function obterRanking( $dataInicio, $dataFinal ) {
		
		$sqlRanking = "select Voo.*, count(Escala.cod_voo) as contagem from Voo left join Escala on Escala.cod_voo = Voo.codigo
			where data_partida >= '{$dataInicio}' and data_chegada <= '{$dataFinal}'
			group by Voo.codigo order by contagem desc, Voo.codigo";

		$vetor = DB::executarConsulta( $sqlRanking );
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

  	$sqlVoos    = "select pais, count(Voo.codigo) as contagem, datediff(data_chegada, '{$dataInicio}' ) div 7 as semana from Voo right join Aeroporto on Voo.cod_destino = Aeroporto.codigo where data_partida >= '{$dataInicio}' and data_chegada <= '{$dataFinal}' group by pais,semana order by semana,pais";

  	$data    = Carbon::createFromFormat('Y-m-d H', $dataInicio.' 00');
  	$last = -1;
		$tmp     = '';
		$json = [];
		$arr  = DB::executarConsulta( $sqlVoos );
		$var;

		foreach( $arr as $item ) {

			$week   = intval( $item['semana'] );
			if( $last != $week ) {
				$tmp  = $data->addWeeks( $week )->format('d/m/Y');
				$last = $week;
				$var  = $data;
			}
			array_key_exists( $tmp, $json ) ?: $json[ $tmp ] = [ 'fim' => $var->addDays(6)->format('d/m/Y') ];
			$json[ $tmp ][ 'dados' ][] = $item;
		}

		return $json;
  }

}
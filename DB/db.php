<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 15:22:04
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-22 19:31:07
 */

namespace DB;
use PDO;

class DB {
	private $system;

  //Variáveis para a conexão com o banco
  private static $host = 'localhost';
  private static $nome = 'saci';
  private static $usuario = 'root';
  private static $senha = 'baseLocal';
  private static $conexao;

  //Funcão para execução de um comando SQL
  public static function executarComando( $sql ) {

    //Criação do objeto para a execução do comando
    $conn  	 = mysqli_connect( self::$host, self::$usuario, self::$senha, self::$nome );
    $conn->set_charset( 'utf8' );
    $query   = $conn->query( $sql );
    $resul = [ 'affected_rows' => $conn->affected_rows, 'insert_id' => $conn->insert_id ];
    $conn->close();
    
    return $resul;
  }

  //Funcão para execução de uma busca na base de dados
  public static function executarConsulta( $sql ) {

    //Criação do objeto para a execução do comando
		$conn  = mysqli_connect( self::$host, self::$usuario, self::$senha, self::$nome );
    $conn->set_charset( 'utf8' );
    $query = $conn->query( $sql );
    $arr 	 = $query->fetch_all( MYSQLI_ASSOC );
    $query->close();
    $conn->close();
  	return $arr;
  }

}

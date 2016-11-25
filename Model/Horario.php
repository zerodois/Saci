<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-21 18:03:36
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-11-22 09:02:52
 */

namespace Model;
include_once '../DB/db.php';
use DB\DB;

class Horario {

	private $data;
	private $hora;
	private $db;
	private $sql = 'insert into Horario values (\'%s\', \'%s\')';

	public function __construct( $data ) {
		$this->db = new DB();
		$this->hora = array_key_exists( 'hora', $data ) ? $data['hora'] : null;
		$this->data = array_key_exists( 'data', $data ) ? $data['data'] : null;
	}

	public function getData() {
		return $this->data;
	}
	public function setData( $data ) {
		$this->data = $data;
		return $this;
	}

	public function getHora() {
		return $this->hora;
	}
	public function setHora( $hora ) {
		$this->hora = $hora;
		return $this;
	}

	public function gravar() {
		return $this->db->executarComando( sprintf($this->sql, $this->data, $this->hora) );
	}
}

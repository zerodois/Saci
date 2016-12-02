<?php
/**
 * @Author: Felipe J. L. Rita
 * @Date:   2016-11-30 17:19:22
 * @Last Modified by:   Felipe J. L. Rita
 * @Last Modified time: 2016-12-01 19:07:23
 */

include_once 'DB/db.php';

use DB\DB;

$sql = "UPDATE Voo set status='confirmado' WHERE status='ativo' and TIMESTAMPDIFF( HOUR, now(), CONCAT(data_partida, ' ', hora_partida) ) between 0 and 5";

DB::executarComando( $sql );
<?php
use Database\Object as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;

class test_load_db extends \LiteTest\TestCase{
	function test_db_load(){
		
		$sql = \Database\SqlObject::get_instance();
		// var_dump($sql);
		$rr = mysqli_query($sql->db_connection, "SELECT * from `my_items` WHERE `slug`=180727;");
		var_dump($rr);
		var_dump(mysqli_fetch_assoc($rr));
		$r = $sql->pdo->query("select * from `my_items` where slug=180727");
		var_dump($r->fetchAll());
	}
}
?>
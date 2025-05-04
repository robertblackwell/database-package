<?php
namespace Database;

use \Exception as Exception;
use Database\iSqlIzable;

/**
* @brief This class provides static function to create a database if it does ot exist.
*
* In this version it uses mysql
*
* @todo put error checking on the insert to ensure proper handling of duplicate primary keys
* @todo need to update to use mysqli rather than mysql
 * @todo convert to PDO
*/
class DbCreator
{
	public static function db_init_with_create(array $config)
	{
		if (($config == null)||(count($config) == 0)) throw new \Exception("database ".__FUNCTION__." config not set");
		try {
			\Database\SqlObject::init($config["sql"]);
			\Database\Locator::init($config['hed']);
			return;
		} catch(\Exception $e) {
			$db_name = $config["sql"]["db_name"];
			$host = $config["sql"]["db_host"];
			$user = $config["sql"]["db_user"];
			$pwd = $config["sql"]["db_passwd"];
			$conn = mysqli_connect($host, $user, $pwd);
			if ($conn === false) {
				throw new \Exception(
					"could not connect to data base db:$db_name user:$user in ".__FILE__." at line ".__LINE__
				);
			}
			$r = $conn->query("CREATE DATABASE {$db_name}");
			if(!($r === true)) {
				throw new \Exception("database create of {$db_name} failed");
			}
			$conn->close();
			\Database\SqlObject::init($config["sql"]);
		}
	}
}

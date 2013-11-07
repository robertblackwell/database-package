<?php
/*! 
* @namespace
* The namespace Database is the top level namespace for this package.
*
*	-	Database.Object is the object through which the database can be initialized and configured
*	
*
*/
namespace Database;
/*!
** Documentation for a name space
*/
use \Database\SqlObject as SQLObject;
use \Database\Locator as Locator;
use \Exception as Exception;
use \Database\Models\RowObject as RowObject;
use \Database\HED\HEDObject as HEDObject;
use \Registry as Registry;
/*!
** Documentation for a class
*/
class Object
{
	static $sql;  //an object that knows how to interface to the sql database
	static $locator;
	var $configuration;
	static $_instance;
	static function init($configuration){
        SQLObject::init($configuration['sql']);
		Locator::init($configuration['hed']);
		$inst = new Object();
		$inst->configuration = $configuration;
		self::$sql = SQLObject::get_instance();
		self::$locator = Locator::get_instance();
		self::$_instance = $inst;

		Models\Base\ModelBase::$sql = self::$sql;
		Models\Base\ModelBase::$locator = self::$locator;
		Models\Factory::$sql = self::$sql;
		Models\Factory::$locator = self::$locator;
	}
	static function setConfig($config_object){
	    $config = array();
	    $config['sql'] = array(
				'db_name'=>Registry::$globals->db['db_name'],
				'db_user'=>Registry::$globals->db['db_user'],
				'db_host'=>Registry::$globals->db['db_host'],
				'db_passwd'=>Registry::$globals->db['db_passwd'],
				);
	    
	    $config['hed'] = array(
				'data_root'=>Registry::$globals->data_root,
				'doc_root'=>Registry::$globals->doc_root,
				'full_url_root'=>Registry::$globals->url_root,
				'url_root'=>Registry::$globals->url_root,
				);
		self::init($config);
	}
	static function get_instance(){
		return self::$_instance;
	}
}
?>
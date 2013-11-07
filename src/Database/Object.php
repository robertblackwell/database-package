<?php
/*! 
* The namespace Database is the top level namespace for this package.
*
*	-	Database.Object which is the main interface object
*	
*	-	Database.Locator. This object provides methods for navigating around and finding things in the
* 		flat file database which is the core long term repository of blog content in this database. Go to the
*		documentation for this class Database.Locator for details of how the flat file structure works.
*	
*	-	Database.SqlObject provides a simple layer over the top of mysql(i) for accessing the sql database that is
*		used as an index to provide filtered access to sets of blog posts and selections of otehr objects.
*
* 
*
*/
namespace Database;
use \Database\SqlObject as SQLObject;
use \Database\Locator as Locator;
use \Exception as Exception;
use \Database\Models\RowObject as RowObject;
use \Database\HED\HEDObject as HEDObject;
use \Registry as Registry;
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
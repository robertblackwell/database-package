<?php
/**
* @brief The Database module implements the blog database for the whiteacorn travel website.
* 
*/
namespace Database;

use \Database\SqlObject as SQLObject;
use \Database\Locator as Locator;
use \Exception as Exception;
use \Database\Models\RowObject as RowObject;
use \Database\HED\HEDObject as HEDObject;
use \Registry as Registry;

/**
* @brief The class Database provides a means of initializing and configuring the blog database machinery
*/
class Object
{
	/**
	* Holds a reference to an Sql object - convenience so that dont have to keep getting it
	*/
	public static $sql;  //an object that knows how to interface to the sql database

	/**
	* Holds a reference to a Locator object - convenience so that dont have to keep getting it
	*/
	public static  $locator;

	var $configuration;
	
	/**
	* A reference to the singleton instance of this class
	*/
	private static $instance;

	/**
	* 	Initializes the Database system - allocates singleton instances, gives config information
	* 	to the Locator and the SqlObject
	*
	*	@param array $configutarion
	*
	*/
	public static function init($configuration)
	{
		/**
		* Allocate and prime the SqlObject
		*/
        SQLObject::init($configuration['sql']);
		/**
		* Allocate and prime the Locator object
		*/
		Locator::init($configuration['hed']);
		/**
		* Now allocate the singleton of this class and hook in the Locator and SqlObject
		*/
		$inst = new Object();
		$inst->configuration = $configuration;
		self::$sql = SQLObject::get_instance();
		self::$locator = Locator::get_instance();
		self::$instance = $inst;

		/**
		* @note at this point we also hook the Locator and SqlObject into the base Model class 
		* and the model Factory as a static so that all instances of these classes have access
		*/
		Models\Base\ModelBase::$sql = self::$sql;
		Models\Base\ModelBase::$locator = self::$locator;
		Models\Factory::$sql = self::$sql;
		Models\Factory::$locator = self::$locator;
	}
	
	/**
	* Allows a configuration array to be set from the global Registry object
	* @deprecated
	*/
	static function setConfig($config_object)
	{
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
				'url_data_root'=>'dataroot',
				'full_url_root'=>Registry::$globals->url_root,
				'url_root'=>Registry::$globals->url_root,
				);
		self::init($config);
	}
	/**
	* Gets the singleton instance of a Database\Object class
	*
	* @return Database\Object
	* 
	*/ 
	public static function get_instance()
	{
		return self::$instance;
	}
}
?>
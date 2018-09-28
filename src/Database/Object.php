<?php
/**
* @brief The Database module implements the blog database for the whiteacorn travel website.
*
*/
namespace Database;

use \Database\SqlObject as SQLObject;
use \Database\Locator as Locator;
use \Exception as Exception;
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
	public static $locator;

	public $configuration;
	
	/**
	* A reference to the singleton instance of this class
	*/
	private static $instance;

	/**
	 * Initializes the Database system - allocates singleton instances, gives config information
	 * to the Locator and the SqlObject
	 *
	 * @param array $configuration Config details.
	 * @return void
	 *
	 */
	public static function init(array $configuration)
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
		// Models\Base\ModelBase::$sql = self::$sql;
		// Models\Base\ModelBase::$locator = self::$locator;
		Models\Base\Model::$sql = self::$sql;
		Models\Base\Model::$locator = self::$locator;
	}
	/**
	* Gets the singleton instance of a Database\Object class
	*
	* @return \Database\Object
	*
	*/
	public static function get_instance()
	{
		return self::$instance;
	}
}

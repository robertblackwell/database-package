<?php
namespace Database\Models\Base;

use Database\Models\CategorizedItem;
use \Exception as Exception;
use Database \iSqlIzable;

/**
** @defgroup Models
** Models are classes that represent entities in the database and are defined to suite the application
** domain.
**
** Each model should be derived from the class ModelBase
*/

/*!
** @ingroup Models
**  This is the base class for all model classes.
**  It is the first place in the class hierarchy that knows about field names
**
*/
class Model extends Row implements iSqlIzable
{
	/**
	* @var $sql \Database\SqlObject - used for models to make sql calls. Set up during initialization
	*/
	public static $sql;
	/**
	* @var $locator \Database\Locator - used to locate data entities in the flat file system. Set up during
	* initialization
	*/
	public static $locator;

	/**
	* @var $table string - the name of the sql table/view this model is connected to. Each derived class
	* MUST provide a value for this property
	*/
	protected $table;     //name of the corresponding SQL table
	protected $sql_primary_key;
	/**
	* Constructor.
	* @param array $obj Sql result row.
	* @return Model
	*/
	public function __construct(array $obj)
	{
		//print __CLASS__.":".__METHOD__.":";
		parent::__construct($obj);
	}
	/**
	* @return string Name of the primary key property.
	*/
	public function getSqlPrimaryKey() : string
	{
		return $this->sql_primary_key;
	}
	/**
	* @return array Of string names of the properties from this
	*               odel that appear in the sql tabke.
	*/
	public function getSqlProperties() :array
	{
		return $this->sql_properties;
	}
	/**
	* @return string Name of the associated sql table or view
	*/
	public function getSqlTable() : string
	{
		return $this->table;
	}

	/**
	* Beginning of explicit properties.
	* This function checks an array/row has a key and returns its value with the correct type
	* @param array  $row  Associative array from sql query.
	* @param string $key  Property name to be extracted from array.
	* @param string $type Type id of the property to be extracted.
	* @return mixed.
	* @throws \Exception If $key is not in $row or $type is invalid.
	*
	*/
	protected function get_property_value(array $row, string $key, string $type)
	{
		//print "<h1>".__METHOD__."($field) </h1>";
		if (!in_array($type, self::$validTypes)) {
			throw new \Exception("{$type} is invalid type");
		}
		if (!isset($row[$key])) {
			throw new \Exception("{$key} is not present in row");
		}
//		$typ = $this->properties[$key];
		//var_dump($cc::$fields);
		$method="get_".$type;
		$v = $this->$method($key);
		return $v;
	}
	/*
	** Below here are a set of common "finder" functions
	*/
	// static function getBySlug($slug)
	// {
	// }
	/**
	* find Modle objects subject to the where clause.
	* @param string $where Where clause for a select statement.
	* @return array|null Of Model objects.
	*/
	public static function findWhere(string $where)
	{
		$c = $where;
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	   
	/*
	** Below here are a small set of standard sql operations
	*/
	/**
	* Inserts this instance of a derived class into the sql database.
	* @return void Should throw if fails.
	*/
	public function sql_insert()
	{
		self::$sql->insert($this->table, $this);
	}
	/**
	* Updates this instance of a derived class in the sql database.
	* @return void
	*/
	public function sql_update()
	{
		self::$sql->update($this->table, $this);
	}
	/**
	* Deletes this instance of a derived class fomr the sql database.
	* @return void
	*/
	public function sql_delete()
	{
		self::$sql->delete($this->table, $this);
		CategorizedItem::delete_slug($this->slug);
	}
}

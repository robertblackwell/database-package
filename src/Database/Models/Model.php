<?php
namespace Database\Models;

use Database\Models\CategorizedItem;
use \Exception as Exception;
use Database\SqlObject;
use Database\Locator;
use Database\iSqlIzable;

/**
* Provides base class for all models that require sql functions, and common constructor signature to models.
*/
class Model implements iSqlIzable
{
	/**
	* @var $sql \Database\SqlObject - used for models to make sql calls. Set up during initialization
	*/
	protected static $sql;
	/**
	* @var $locator \Database\Locator - used to locate data entities in the flat file system. Set up during
	* initialization
	*/
	protected static $locator;

	/**
	* @var string $table The name of the sql table/view this model
	*                    is connected to. Each derived class MUST provide a value.
	*/
	protected $table;     //name of the corresponding SQL table

	/**
	* @var array $sql_properties An array of names of all the properties of this model
	*                            that appear in the table associated with this model.
	*                            Each derived class MUST provide a value.
	*/
	protected $sql_properties;

	/**
	* @var string $sql_primary_key A string name of this models primary key property.
	*                            Each derived class MUST provide a value.
	*/
	protected $sql_primary_key;
	
	/**
	* Initialize the class - statically.
	* @param SqlObject $sql     The current SqlObject.
	* @param Locator   $locator The current instance of the Locator.
	* @return void
	*/
	public static function init(SqlObject $sql, Locator $locator) : void
	{
		self::$sql = $sql;
		self::$locator = $locator;
	}
	public static function get_field_names()
	{
		return static::$field_names;
	}
	/**
	* Constructor.
	* @return Model
	* @todo - provide a type hint for $obj. Must be an associative array of HEDObject.
	* maybe ArrayAccess is the best super class
	*/
	public function __construct($obj)
	{
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
	public function getSqlProperties() : array
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

	/*
	** Below here are a set of common "finder" functions
	*/
	// static function getBySlug($slug)
	// {
	// }
	/**
	* find Model objects subject to the where clause.
	* @param string $where Where clause for a select statement.
	* @return array|null Of Model objects.
	*/
	public static function findWhere(string $where) : ?array
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

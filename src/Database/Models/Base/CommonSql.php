<?php
namespace Database\Models\Base;

use Database\Models\CategorizedItem;
use \Exception as Exception;
/**
* Provides common sql functions to models.
*/
class CommonSql
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
	* @var $table string - the name of the sql table/view this model is connected to. Each derived class
	* MUST provide a value for this property
	*/
	protected $table;     //name of the corresponding SQL table
	
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

<?php
namespace Database;

use \Exception as Exception;
use Database\iSqlIzable;

/**
* @brief This class provides a simple abstraction layer over the top of the sql database.
*
* In this version it uses mysql
*
* @todo put error checking on the insert to ensure proper handling of duplicate primary keys
* @todo need to update to use mysqli rather than mysql
 * @todo convert to PDO
*/
class SqlObject
{
	/**
	* @var \Database\SqlObject $instance Holds a reference to the singleton instance of this class
	*/
	private static $instance;
	/**
	 * @var \ConfigObject $config Database config details
	 */
	private static $config = null;
	/**
	 * @var \mysqli $db_connection Database connection
	 */
	public $db_connection = null;
	public $pdo;
	public $db_name;
	/**
	* Configures and opens a connection to the sql database server and selects the correct database
	* as the current db.
	 * @param array $config DB config details.
	 * @return void
	*/
	public static function init(array $config)
	{
		self::$config = $config;

		$inst = new SqlObject();
		self::$instance = $inst;

		$inst->db_init();
	}
	/**
	 * Returns the singleton instance of this class.
	 * @return \Database\SqlObject
	 */
	public static function get_instance()
	{
		return self::$instance;
	}
	/**
	* Initialize a database connection. Precondition - config has been set
	 * @return void
	 * @throws \Exception On error.
	*/
	private function db_init()
	{
		if (! self::$config) throw new \Exception("database ".__FUNCTION__." config not set");
		$db_name = self::$config["db_name"];
		$this->db_name = $db_name;
		$host = self::$config["db_host"];
		$user = self::$config["db_user"];
		$pwd = self::$config["db_passwd"];
        $db_port = (array_key_exists('db_port', self::$config)) ? self::$config["db_port"] : null;
		//var_dump(self::$config);
		//print "<h2>".__METHOD__."() db_name[$db_name] host[$host] user[$user] pwd[$pwd]</h2>";
		try {
			$conn = (is_null($db_port)) ? mysqli_connect($host, $user, $pwd, $db_name) : mysqli_connect($host, $user, $pwd, $db_name, $db_port);
			if (! isset($conn)) {
				throw new \Exception(
					"could not connect to data base db:$db_name user:$user in ".__FILE__." at line ".__LINE__
				);
			}
		} catch (\Exception $e) {
			throw $e;
			throw new \Exception(
				"could not connect to data base db:$db_name user:$user in ".__FILE__." at line ".__LINE__
			);
		}
		$this->db_connection = $conn;

		$charset = 'utf8';
		$dsn = ($db_port) ?  "mysql:host=$host;dbname=$db_name;charset=$charset;port=3307" : "mysql:host=$host;dbname=$db_name;charset=$charset" ;
		$opt = [
			\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			\PDO::ATTR_EMULATE_PREPARES   => false,
		];
		$this->pdo = new \PDO($dsn, $user, $pwd, $opt);
	}
	/**
	 * select a db
	 * @return void
	 */
	private function select_db()
	{
		mysqli_select_db($this->db_connection, $this->db_name);
	}
	/**
	 * set the global db config values
	 *
	 * @param array $config With the fields db_name, db_user, db_passwd set.
	 * @return void
	 */
	public function set_config(array $config)
	{
		$this->_config = $config;
		//print "\nsetConfig ". __FUNCTION__ ."\n";
		//var_dump(self::$config);
	}

	/**
	 * Gets the fields in a table.
	 * @param string $table Name of a table.
	 * @return array Of field/column attributes as returned by mysqli.
	 * @throws \Exception On error.
	 */
	public function getFields(string $table) : array
	{
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		$n = [];
		$this->select_db();
		$result = mysqli_query($this->db_connection, "SHOW FIELDS IN ".$table.";") ;
		//var_dump($result);exit();
		if ($result === false)
			throw new \Exception(__METHOD__." could not SHOW FIELDS for $table ".mysqli_error($this->db_connection));

		while ($row = mysqli_fetch_assoc($result)) {
			$n[] = $row;
		}
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		return $n;
	}
	/**
	* Gets the names (only) of the fields in a table
	* @param string $table Name of a table.
	* @return array of string
	*
	*/
	public function getFieldNames(string $table) : array
	{
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		$flds = $this->getFields($table);
		$a = [];
		foreach ($flds as $f) {
			$a[] = $f['Field'];
		}
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		return $a;
	}

	/**
	 * Get an array of table names in this currently selected data base
	 * @return array  of table names as strings
	 * @throws \Exception On error.
	 */
	public function getTables() : array
	{
		$n = [];
		$result = mysqli_query($this->db_connection, "SHOW TABLES;");
		if ($result === false)
			throw new \Exception("could not SHOW TABLES ".mysqli_error());
		while ($row = mysqli_fetch_assoc($result)) {
			$n[] = $row["Tables_in_".strtolower(self::$config['db_name'])];
		}
		return $n;
	}
	/**
	* Performs a select against the given view or table and returns the
	* result as an sql_result object.
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysqli format
	*
	* @param string $table    THe name of the table.
	* @param string $criteria The search criteria.
	* @return mixed An sql_result.
	* @throws \Exception If query is bad indicated by a null result from the query.
	*/
	public function select(string $table, string $criteria = "")
	{
		//print "<p>".__CLASS__."::".__METHOD__."($table, $criteria)</p>";
		$a = [];
		$query = "SELECT * FROM $table";
		if (($criteria != null) && ($criteria != "")) {
			$query .= " ".$criteria.";";
		}
		$result = mysqli_query($this->db_connection, $query);
		if ($result === false)
			throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);

		return $result;
	}

	/**
	* Performs a select against the given view or table and returns an array
	* of model objects ONLY.
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysqli format
	*
	* @Note the class of the objects returned is specified as the second parameter
	*
	* @param string $table    Name of table.
	* @param string $class    The name of the class for the value objects to create.
	*                          The constructor of class must accept an associative array
	*						   of field names and values.
	* @param string $criteria The search criteria.
	* @return array Of model object.
	*
	* @throws Exception When query fails.
	*
	*/
	public function select_array_of_objects(
		string $table,
		string $class,
		string $criteria = ""
	) : array
	{
		$a = [];
		$query = "SELECT * FROM $table";
		if (($criteria != null) && ($criteria != "")) {
			$query .= " ".$criteria.";";
		}
		$result = mysqli_query($this->db_connection, $query);
		if ($result === false)
			throw new \Exception(
				"could not do a query $query in " . __FILE__ . " at line " . __LINE__ . " "
				. mysqli_error($this->db_connection) . "  " . $criteria
			);
		while ($row = mysqli_fetch_assoc($result)) {
			$a[] = new $class($row);
		}
		return $a;
	}

	/**
	* Performs a select against the given view or table and returns a SINGLE
	* of model objects ONLY.
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysqli format
	*
	* @Note the class of the objects returned is specified as the second parameter
	*
	* @param string $table    Name of table.
	* @param string $class    The name of the class for the value objects to create.
	*                          The constructor of class must accept an associative array
	*						   of field names and values.
	* @param string $criteria The search criteria.
	* @return mixed Model instamce or null.
	*
	* @throws Exception When query fails.
	*
	*/
	public function select_single_object(
		string $table,
		string $class,
		string $criteria = ""
	)
	{
		$ifaces = class_implements($class);
		$a = [];
		$query = "SELECT * FROM $table";
		if (($criteria != null) && ($criteria != "")) {
			$query .= " ".$criteria.";";
		}
		$result = mysqli_query($this->db_connection, $query);
		if ($result === false)
			throw new \Exception(
				"could not do a query $query in " . __FILE__ . " at line " . __LINE__ . " "
				. mysqli_error($this->db_connection) . "  " . $criteria
			);
		while ($row = mysqli_fetch_assoc($result)) {
			$a[] = new $class($row);
		}
		if (count($a) == 1) return $a[0];
		if (count($a) == 0) return null;
		throw new \Exception("got multiple models returned");
	}

	/**
	* Performs a select against the given view or table and returns an array
	* of model objects, or a single model or null.
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysqli format
	*
	* @Note the class of the objects returned is specified as the second parameter
	*
	* @param string  $table        Name of table.
	* @param string  $class        The name of the class for the value objects to create.
	*                             The constructor of class must accept an associative array
	*							  of field names and values.
	* @param string  $criteria     The search criteria.
	* @param boolean $array_always When true returns an array even for 1 or zero results.
	* @return mixed Either an array() of objects or a single model object.
	*
	* @throws Exception When query fails.
	*
	* @todo - seems to be a problem - what if no rows are found, wont that throw an exception
	* @todo - this is a problem as its too difficult to understand the return type
	* @todo - would like to deprecate this function
	*/
	public function select_objects(
		string $table,
		string $class,
		string $criteria = "",
		bool $array_always = true
	)
	{
		$a = $this->select_array_of_objects($table, $class, $criteria);
		if ((count($a) == 1)&&(! $array_always)) return $a[0];
		if ((count($a) == 0)&&(! $array_always)) return null;
		return $a;
	}


	/**
	* performs a query
	* @param string $query Is a complete sql query as a string.
	*
	* @return mixed An sql_result object.
	*
	* @throws Exception When query fails.
	*
	* @todo - seems to be a problem - what if no rows are found, wont that throw an exception
	*/
	public function query(string $query)
	{
		$a = [];
		$result = mysqli_query($this->db_connection, $query) ;
		if ($result === false)
			throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
		return $result;
	}

	/**
	* performs a query
	* @param string  $query        A complete sql query string.
	* @param string  $class        The name of the class to construct from each query result.
	* @param boolean $array_always When true always return an array of objects even for 1 or zero results.
	* @return mixed Either an array() of model objects or a single model object.
	*
	* @throws Exception When query fails.
	*
	* @todo - seems to be a problem - what if no rows are found, wont that throw an exception
	*/
	public function query_objects(string $query, string $class, bool $array_always = true)
	{
		$a = [];
		$result = mysqli_query($this->db_connection, $query);
		if ($result === false)
			throw new Exception(
				"could not do a query $query in ".__FILE__
				." at line ".__LINE__." ".mysqli_error($this->db_connection)
			);

		while ($row = mysqli_fetch_assoc($result)) {
			$a[] = new $class($row);
		}
		if ((count($a) == 1)&&(! $array_always)) return $a[0];
		if ((count($a) == 0)&&(! $array_always)) return null;
		return $a;
	}
	/**
	* performs a query that returns a single model object or null
	* @param string  $query        A complete sql query string.
	* @param string  $class        The name of the class to construct from each query result.
	* @param boolean $array_always When true always return an array of objects even for 1 or zero results.
	* @return mixed Either a single model object or null.
	*
	* @throws Exception When query fails.
	*
	* @todo - seems to be a problem - what if no rows are found, wont that throw an exception
	*/
	public function query_single_object(string $query, string $class)
	{
		$a = [];
		$result = mysqli_query($this->db_connection, $query);
		if ($result === false)
			throw new Exception(
				"could not do a query $query in ".__FILE__
				." at line ".__LINE__." ".mysqli_error($this->db_connection)
			);

		while ($row = mysqli_fetch_assoc($result)) {
			$a[] = new $class($row);
		}
		if ((count($a) == 1)) return $a[0];
		if ((count($a) == 0)) return null;
		throw new \Exception('should not have found more than a single row');
	}

	/**
	* performs a query that can return an array of zero or more objects
	* @param string  $query        A complete sql query string.
	* @param string  $class        The name of the class to construct from each query result.
	* @return array An array() of zero or more model objects.
	*
	* @throws Exception When query fails.
	*
	* @todo - seems to be a problem - what if no rows are found, wont that throw an exception
	*/
	public function query_array_of_objects(string $query, string $class) : array
	{
		$a = [];
		$result = mysqli_query($this->db_connection, $query);
		if (! $result)
			throw new Exception(
				"could not do a query $query in ".__FILE__
				." at line ".__LINE__." ".mysqli_error($this->db_connection)
			);

		while ($row = mysqli_fetch_assoc($result)) {
			$a[] = new $class($row);
		}
		return $a;
	}


	/**
	* update a row in a table that corresponds to the given object
	* BEWARE assuming the primary key is called id
	 *
	 * @param string     $table  Name of the table to be updated.
	 * @param iSqlIzable $object The new value of the object to be updated.
	 * @return void
	 * @throws Exception When query fails.
	 *
	 */
	public function update(string $table, iSqlIzable $object)
	{
		//print "<p>".__METHOD__."($table)</p>";
		//print "<p>".get_called_class()."</p>";
		//var_dump($this->getFieldNames($table));
		//var_dump($object->getFieldNames());
		$tflds = $this->getFieldNames($table);
		$oflds = $object->getSqlProperties(); //FieldNames();
		$i_flds = array_intersect($tflds, $oflds);
		//var_dump($i_flds);
		$query = "UPDATE $table SET ";
		$f = get_object_vars($object);
		$s = "";
		$first=true;
		$fields = $object->getFieldNames();
		$row = $object->to_row();
		foreach ($i_flds as $k) {
			$v = $row[$k];
			if (($k != "slug")) {
				if ($first) {
					$s = $s .  $k . "='". mysqli_real_escape_string($v) ."' ";
					$first= false;
				} else {
					$s = $s . ", " .  $k . "='". mysqli_real_escape_string($v) ."' ";
				}
			}
		}
		$query = $query . $s . " WHERE slug='". $object->slug ." ' ;";
		$result = mysqli_query($this->db_connection, $query);
		if (! $result)
			throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
		//print "\n<p>".__FUNCTION__. " query:[$query] </p>\n";
	}
	/**
	 * @param string $table Name of table for which the primary key is required.
	 * @return string Name of the primary key field.
	 * @throws Exception When table does not exist or does not have primary key.
	 */
	public function get_primary_key(string $table) : string
	{
		$query = "show fields from $table";
		$result = mysqli_query($this->db_connection, $query);
		if (! $result)
			throw new \Exception("could not do a query $query in " . __METHOD__ . " at line " . __LINE__);
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['Key'] == 'PRI') {
				return $row['Field'];
			}
		}
		throw new \Exception(__METHOD__."No primary key found for table: $table");
	}

	/**
	 * Deletes the row represented by the object from a table
	 * @param string     $table  The name of the table.
	 * @param iSqlIzable $object The object representing the row to be deleted.
	 * @return void
	 * @throws \Exception If the delete query fails.
	 */
	public function delete(string $table, iSqlIzable $object)
	{
		$p_key = 'slug'; //$object->getSqlPrimaryKey();
		$k = $this->get_primary_key($table);
		//var_dump($k);
		if ($k != 'slug') {
			$p_key = $k;
		}
		$query = "DELETE FROM $table WHERE $p_key='" . $object->$p_key . "'";
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysqli_query($this->db_connection, $query);
		if (! $result)
			throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
	}

	/**
	 * Insert a new row represented by the object into a table.
	 * The values inserted are those in common between fields/properties of the object
	 * and columns of the table.
	 *
	 * @param string     $table       The name of the table.
	 * @param iSqlIzable $object      A model object to be inserted.
	 * @param boolean    $throw_error A flag to indicate whether or not.
	 *                                                 not to throw an exception on error.
	 * @return void This should be changed @todo string The id of the inserted row.
	 * @todo - this should return an id
	 * @throws \Exception If the insert fails.
	 */
	public function insert(string $table, iSqlIzable $object, bool $throw_error = true) //: void
	{
		//print "<p>".__METHOD__."( $table class of object is ".get_class($object)." )\n</p>";
		//print "<p>".get_called_class()."</p>";
		//var_dump($this->getFieldNames($table));
		//var_dump($object->getFieldNames());
		//exit();
		$tflds = $this->getFieldNames($table);
		$oflds = $object->getSqlProperties();
		$i_flds = array_intersect($tflds, $oflds);
		//var_dump($i_flds);
		$query = "INSERT INTO $table ";
		$f = get_object_vars($object);
		$cols = "";
		$vals = "";
		$first=true;
		//$row = $object->to_row();
		foreach ($i_flds as $k) {
			//$v = $row[$k];
			$v = $object->$k;
			if (is_object($v)) {
				throw new \Exception("<p>object found not string</p>");
				//var_dump($v);
				exit();
			}
			if (is_null($v)) {
				continue;
			}
			if (($k != "id")) {
				if ($first) {
					$cols = $cols . $k;
					$vals = $vals . "'" . mysqli_real_escape_string($this->db_connection, $v) . "'";
					$first= false;
				} else {
					$cols = $cols . ", " .  $k;
					$vals = $vals . ", '". mysqli_real_escape_string($this->db_connection, $v) ."' ";
				}
			}
		}
		$query = $query . "($cols) VALUES(" . $vals . " );";
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysqli_query($this->db_connection, $query);
		//var_dump($result);
		if ($throw_error &&  ! $result)
			throw new \Exception(
				"could not do a query $query in ".__FILE__
				." at line ".__LINE__. " ". mysqli_error($this->db_connection)
			);
		//print "<p>Database::insert ". mysqli_insert_id($this->db_connection)."</p>";
		//$object->id = mysqli_insert_id($this->db_connection);
	}
	/**
	 * @param string $table Name of table to truncate.
	 * @return void
	 * @throws Exception If sql exec fails.
	 */
	public function truncate(string $table) : void
	{
		$query = "TRUNCATE  TABLE $table ";
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysqli_query($this->db_connection, $query);
		if (! $result)
			throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
	}
}

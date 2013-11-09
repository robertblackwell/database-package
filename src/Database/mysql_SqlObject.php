<?php
namespace Database;
/*!
* @ingroup database_
*
* This class provides an abstraction layer over the top of the sql database. 
*
* @todo put error checking on the insert to ensure proper handling of duplicate primary keys
*/
class SqlObject
{
    private static $_instance;
	private static $_config=NULL;
	
	private $_dbconnection=NULL;
	/**
	* Constructor - opens a connection to the database and selects it as the current sb.
	*/
	public static function init($config)
	{
	    self::$_config = $config;
	    
	    $inst = new SqlObject();
	    self::$_instance = $inst;
	    
		$inst->db_init();
	}
	static function get_instance(){
	    return self::$_instance;
	}
	/**
	* Initialize a database connection. Precondition - config has been set
	*/
	private function db_init()
	{
		if (!self::$_config) throw new \Exception("database ".__FUNCTION__." config not set");
		$db_name = self::$_config["db_name"];
		$this->db_name = $db_name;
		$host = self::$_config["db_host"];
		$user = self::$_config["db_user"];
		$pwd = self::$_config["db_passwd"];
		//var_dump(self::$_config);
		//print "<h2>".__METHOD__."() db_name[$db_name] host[$host] user[$user] pwd[$pwd]</h2>";
		$conn = mysql_connect($host, $user, $pwd) 
			or die("could not connect to data base db:$db user:$user in ".__FILE__." at line ".__LINE__);
		mysql_select_db($db_name, $conn) 	
			or die("could not select data base db:$db_name user:$user in ".__FILE__." at line ".__LINE__);
		$this->_dbconnection = $conn;		
	}
	function select_db(){
		mysql_select_db($this->db_name, $this->_dbconnection); 	
	}
	/**
	* set the global db config values
	* @param array() with the fields db_name, db_user, db_passwd set
	*/
	public function set_config($config)
	{
		$this->_config = $config;
		//print "\nsetConfig ". __FUNCTION__ ."\n";
		//var_dump(self::$_config);
	}
	/**
	* Gets an array of fields in a table. 
	* @param string table name
	* @return array() of field/column attributes as returned by mysql
	*/
	public function getFields($table)
	{
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		$n = array();
		$this->select_db();
		$result = mysql_query("SHOW FIELDS IN ".$table.";") ;
		//var_dump($result);exit();
		if( !$result ) throw new \Exception(__METHOD__." could not SHOW FIELDS for $table ".mysql_error());
		while ($row = mysql_fetch_assoc($result)) {
		    $n[] = $row;
		}
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
		return $n;
	}
	public function getFieldNames($table){
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
	    $flds = $this->getFields($table);
	    $a = array();
	    foreach($flds as $f){
	        $a[] = $f['Field'];
	    }
		//print "\n".__CLASS__.":".__METHOD__."( $table )\n";
	    return $a;
	}
	/**
	* Get an array of table names in this data base
	* @return array() of table names as strings 
	*/
	public function getTables()
	{
		$n = array();
		$result = mysql_query("SHOW TABLES;");
		if( ! $result ) throw new \Exception("could not SHOW TABLES ".mysql_error());
		while ($row = mysql_fetch_assoc($result)) {
		    $n[] = $row["Tables_in_".strtolower(self::$_config['db_name'])];
		}
		return $n;
	}
	/*!
	* Performs a select against the given view or table and returns the result as an sql_result object 
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysql format
	* 
	* Note: unlike select - the class of the ORMModel returned is not simply the modified name of the
	* table. The primary use of the method is returning an array of child objects from 1-m relationships
	* where the 1-m relationship is a view or table
	*
	* @param $table string  is the name of the table
	* @param $criteria the search criteria
	* @return mixed an sql_result
	* @throws exception if query is bad   
	*/
	public function select ($table, $criteria="")
	{
	    //print "<p>".__CLASS__."::".__METHOD__."($table, $criteria)</p>";
		$a = array();
		$query = "SELECT * FROM $table";
		if (($criteria != null) && ($criteria != "")){ 
			$query .= " ".$criteria.";";
		}
		$result = mysql_query($query); 
		if( ! $result ) throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
		return $result;
	}

	/*!
	* Performs a select against the given view or table and returns an array
	* of value objects. 
	*
	* The where clause is constructed from the $criteria parameter according to the following rules:
	* if criteria is a string then it IS the where clause in mysql format
	* 
	* Note: unlike select - the class of the ORMModel returned is not simply the modified name of the
	* table. The primary use of the method is returning an array of child objects from 1-m relationships
	* where the 1-m relationship is a view or table
	*
	* @param $table string  is the name of the table
	* @param $class the name of the class for the value objects to create 
	*               The constructor of class must accept an associative array of field names and values    
	* @param $criteria the search criteria
	* @param $array_always if false will return an array when more than one result and a single object when
	*						only one object found. When true always returns an array
	* @return mixed either an array() of ORMModel objects or a single model object  
	*/
	public function select_objects ($table, $class, $criteria="", $array_always=true)
	{
	    //print "<p>".__CLASS__."::".__METHOD__."($table, $criteria)</p>";
		$a = array();
		$query = "SELECT * FROM $table";
		if (($criteria != null) && ($criteria != "")){ 
			$query .= " ".$criteria.";";
		}
		$result = mysql_query($query); 
		if( ! $result ) throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__." ".mysql_error());
		while ($row = mysql_fetch_assoc($result)){
			//$a[] = ORMModel::rowToObject($row, ORMModel::makeModelName($return_class)) ;
			//$a[] = ORMModel::rowToModelObject($row, new $model_class()) ;
			//$a[] = static::$factory_class::static::$factory_method($row);
			$a[] = new $class($row);
		}
		//if (count($a)==0) 
		//	return null;
		//var_dump($row);
		//var_dump($a);
		if ((count($a) == 1)&&(!$array_always)) return $a[0];
		if ((count($a) == 0)&&(!$array_always)) return null;
	    //print "<p>".__CLASS__."::".__METHOD__."($table, $criteria)</p>";
		return $a;
	}


	/**
	* performs a query
	* @param $table string, the table name of the class that will be created for each row
	*						only significant when rows are being returned
	* @param $query string  is a complete sql queryString
	* @return an sql_result object  
	*/
	public function query($query)
	{
		$a = array();
		$result = mysql_query($query) ;
		if( ! $result ) 
		    throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
        return $result;
	}

	/**
	* performs a query
	* @param $table string, the table name of the class that will be created for each row
	*						only significant when rows are being returned
	* @param $query string  is a complete sql queryString
	* @return mixed either an array() of ORMModel objects or a single model object  
	*/
	public function query_objects($query, $class, $array_always=true)
	{
		$a = array();
		$result = mysql_query($query); 
		if( ! $result ) 
		    throw new Exception("could not do a query $query in ".__FILE__." at line ".__LINE__." ".mysql_error());

		while ($row = mysql_fetch_assoc($result)){
            /** Only used by the Valuation model
            */
		    //$row_fixed = $this->row_with_type_correction($row, $result);
			//$a[] = ORMModel::rowToObject($row_fixed, ORMModel::makeModelName($table)) ;
			//var_dump($a);exit();
			$a[] = new $class($row);
		}
		if ((count($a) == 1)&&(!$array_always)) return $a[0];
		if ((count($a) == 0)&&(!$array_always)) return null;
		return $a;
	}


	/**
	* update a row in a table that corresponds to the given object
	* BEWARE assuming the primary key is called id
	*/
	public function update($table, $object)
	{
		//print "<p>".__METHOD__."($table)</p>";
		//print "<p>".get_called_class()."</p>";
		//var_dump($this->getFieldNames($table));
		//var_dump($object->getFieldNames());
		$tflds = $this->getFieldNames($table);
		$oflds = $object->getFieldNames();
		$i_flds = array_intersect($tflds, $oflds);
		//var_dump($i_flds);
		$query = "UPDATE $table SET ";
		$f = get_object_vars($object);
		$s = "";
		$first=true;
		$fields = $object->getFieldNames();
		$row = $object->to_row();
		foreach ($i_flds as $k){
		    $v = $row[$k];
			if ( ($k != "slug")){
				if ($first){
					$s = $s .  $k . "='". mysql_real_escape_string($v) ."' ";
					$first= false;
				}else{
					$s = $s . ", " .  $k . "='". mysql_real_escape_string($v) ."' ";
				}
			}
		}
		$query = $query . $s . " WHERE slug='". $object->slug ." ' ;"; 
		$result = mysql_query($query); 
		if( ! $result )
		    throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);
		//print "\n<p>".__FUNCTION__. " query:[$query] </p>\n";
		
	}
	function get_primary_key($table){
	    $query = "show fields from $table";
		$result = mysql_query($query);
		if( ! $result ) throw new \Exception("could not do a query $query in ".__METHOD__." at line ".__LINE__);
		while ($row = mysql_fetch_assoc($result)){
		    if( $row['Key'] == 'PRI' ){
		        return $row['Field'];
            }
		}
		throw new \Exception(__METHOD__."No primary key found for table: $table");
	}
	/*!
	* Deletes the row represented by the object from a table
	* @param $table The name of the table
	* @param ORMModel $object. The object representing the row to be deleted
	*/
	public function delete($table, $object)
	{
	    $p_key = 'slug';
	    $k = $this->get_primary_key($table);
	    //var_dump($k);
	    if( $k != 'slug' )
	        $p_key = $k;
		$query = "DELETE FROM $table WHERE $p_key='".$object->$p_key."'";
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysql_query($query); 
		if( ! $result ) throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);		
	}
	/*!
	* Insert a new row represented by the object into a table.
	* The values inserted are those in common between fields/properties of the object
	* and columns of the table.
	* 
	* @param $table The name of the table
	* @param $object. A ORMModel, DAModel or VOObject - in any case MUST provide a getFieldNames() method. 
	*                   The object representing the row to be deleted
	* @return $id the id of the inserted row
	*/
	public function insert($table, $object, $throw_error=true)
	{
		//print "<p>".__METHOD__."( $table class of object is ".get_class($object)." )\n</p>";
		//print "<p>".get_called_class()."</p>";
		//var_dump($this->getFieldNames($table));
		//var_dump($object->getFieldNames());
		//exit();
		$tflds = $this->getFieldNames($table);
		$oflds = $object->getFieldNames();
		$i_flds = array_intersect($tflds, $oflds);
		//var_dump($i_flds);
		$query = "INSERT INTO $table ";
		$f = get_object_vars($object);
		$cols = "";
		$vals = "";
		$first=true;
		//$row = $object->to_row();
		foreach ($i_flds as $k){
		    //$v = $row[$k];
		    $v = $object->$k;
		    if( is_object($v) ){
		        //print "<p>object found not string</p>";
		        //var_dump($v);
		        throw new \Exception("SQL insert - field value was found to be object");
		    }
			if ( ($k != "id")){
				if ($first){
					$cols = $cols . $k;
					$vals = $vals . "'" . mysql_real_escape_string($v) . "'"; 
					$first= false;
				}else{
					$cols = $cols . ", " .  $k;
					$vals = $vals . ", '". mysql_real_escape_string($v) ."' ";
				}
			}
		}
		$query = $query . "($cols) VALUES(" . $vals . " );"; 
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysql_query($query);
		//var_dump($result);
		if( $throw_error &&  !$result ) 
					throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__. " ". mysql_error());
		//print "<p>Database::insert ". mysql_insert_id($this->_dbconnection)."</p>";
		//$object->id = mysql_insert_id($this->_dbconnection);
	}
	public function truncate($table){
		$query = "TRUNCATE  TABLE $table ";
		//print "\n".__FUNCTION__. "query: $query \n";
		$result = mysql_query($query); 
		if( !$result ) throw new \Exception("could not do a query $query in ".__FILE__." at line ".__LINE__);		
	}
	function error(){
	    return mysql_error();
	}
}
?>
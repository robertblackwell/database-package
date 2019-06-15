<?php
namespace Database;

/**
* An interface to be provided by any object that wants to be inserted int, updated in,
* or deleted from a sql table
*/
// phpcs:disable
interface iSqlIzable
// phpcs:enable
{
	public function __construct($obj);
	/**
	* @return string The name of the sql table or view that this object lives in.
	*/
	public function getSqlTable() : string;
	/**
	* @return array Of strings, the names of properties that are stored in the sql table.
	*
	* Note the object must make these properties publically accessible via $obj->prop
	* style notation.
	*/
	public function getSqlProperties() : array;
	/**
	* @return string The name of the primary key property.
	*/
	public function getSqlPrimaryKey() : string;
}

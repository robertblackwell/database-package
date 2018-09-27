<?php
namespace Database\Models;

/*!
** @ingroup Models
* This class represents a post content item
*/
class Post extends ItemBase
{
	public static $table_name = "my_items";
	public static $field_names = array(
		"version"=>"text",
		"type"=>"text",
		"slug"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",
		"abstract"=>"html",
		"excerpt"=>"text",
		//"excerpt"=>"getter",
		"topic"=>"text",
		"tags"=>"list",
		"categories"=>"list",
		"featured_image"=>"text",
		//"featured_image"=>"getter",
		"main_content"=>"html",
		);
	/**
	* Constructor.
	* @param array $obj Sql query result row as associative array.
	* @return Item
	*/
	public function __construct(array $obj = null)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		//print __CLASS__.":".__METHOD__.":";
		parent::__construct($obj);
	}
	/**
	* Inserts this instance into the sql database and add categories and categorized items as required.
	* @return void.
	*/
	public function sql_insert()
	{
		parent::sql_insert();
		if (!is_null($this->categories))
		foreach ($this->categories as $cat) {
			CategorizedItem::add($cat, $this->slug);
		}
	}
}

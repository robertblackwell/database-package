<?php
namespace Database\Models;

/**
*
* Magic properties
*
* @property string $version
* @property string $type
* @property string $slug
* @property string $status
* @property string $creation_date
* @property string $published_date,
* @property string $last_modified_date
* @property string $trip
* @property string $title
* @property string $abstract
* @property string $excerpt
* @property string $content_path
* @property string $entity_path
*/
class Article extends ItemBase
{
	public static $table_name = "my_items";
	public static $field_names = [
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
		"main_content"=>"include",
		"featured_image"=>"text",
		//"featured_image"=>"getter",
		"content_path" => "text",
		"entity_path" => "text"
		];
	/**
	* Constructor.
	* @param array $obj Associative array.
	* @return Article.
	*/
	public function __construct(array $obj = null)
	{
		$this->properties = self::$field_names;
		$this->table = self::$table_name;
		parent::__construct($obj);
	}
	/**
	* Find all/count Article for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Number to eturn.
	* @return Article|null
	*
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		$where = " where ( trip='".$trip."' and type = 'article' )";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " $where order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Find all the articles for all trips and return them in an array of Article objects
	* @param integer $count Limits the number returned.
	* @return array|null Of Article objects
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type='article' order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* @return string The html (a single <p></p>) excerpt for this journal entry.
	*/
	public function excerpt()
	{
		return $this->abstract;
	}
}

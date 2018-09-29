<?php
namespace Database\Models;

/**
* This class provides a view of the my_items table that returns all the titles of items of type "article".
*
* @property string $trip
* @property string $slug
* @property string $title
* @property string $country
*/
class ArticleTitle extends Base\Model
{
	public static $table_name = "my_items";
	public static $field_names = [
		"trip"    =>"text",
		"slug"    => "text",
		"title"   => "text",
		"country" => "text"
	];

	// var year, month - see DAEntryMonth for confirmation of the attribute names
	/**
	* Constructor.
	* @param array $obj Sql query result as an associative array.
	*/
	public function __construct(array $obj)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		//print "<p>".__METHOD__."</p>";
		//var_dump($obj);
		parent::__construct($obj);
	}
	/**
	* Find those ArticleTitle entities for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Number of items to return.
	* @return null|ArticleTitle
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT slug, trip, title, country FROM my_items WHERE (trip = '"
			 .$trip
			 ."' and type='article') order by title asc";
		return self::$sql->query_objects($c, __CLASS__);
	}
	/**
	* Find all ArticleTitle.
	* @param integer $count Number of items to return.
	* @return null|ArticleTitle
	*/
	public static function find(int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT slug, title, country FROM my_items WHERE ( type='article')   order by title asc";
		return self::$sql->query_objects($c, __CLASS__);
	}
}

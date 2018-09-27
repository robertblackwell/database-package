<?php
namespace Database\Models;

/*!
** @ingroup Models
* This class represents a view of the items table that allows selection of a set of items by category
*/
class CategorizedItem extends Base\Model
{
	public static $table_name = "categorized_items";
	public static $field_names = array(
		"trip" => "text",
		"category"=>"text",
		"item_slug"=>"text",
		);
	/**
	 * CategorizedItem constructor.
	 * @param array $obj Sql query row result as associative array.
	 * @return CategorizedItem
	 */
	public function __construct(array $obj = null)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		// var_dump($obj);
		parent::__construct($obj);
	}
	/**
	* Finds all (or count) the rows in the categorized_items table and returns
	* them as an array of CategorizedItem objects
	* @param integer $count Limit the number returned to the count value.
	* @return array
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "   order by category asc $count_str ";
		return self::$sql->select_objects("categorized_items", __CLASS__, $c);
	}
	/**
	 * Detremines whether a $slug is in a category.
	 * @param string $category The category.
	 * @param string $slug     The $slug for the item.
	 * @return boolean
	 * @throws \Exception On failure.
	 */
	public static function exists(string $category, string $slug) : bool
	{
		$q = "select * from categorized_items where category='".$category."' and item_slug='".$slug."'";
		$r = self::$sql->query_objects($q, __CLASS__, false);
		//var_dump($r);exit();
		$ret = !is_null($r);
		return $ret;
	}
	/**
	* Ensures a category, slug pair are in the categorized_items table. Insert if not there.
	* @param string $category String value of a category.
	* @param string $slug     A slug for an existing item in the my_items table.
	* @return void
	*/
	public static function add(string $category, string $slug)
	{
		\Trace::function_entry();
		$a = array('category'=>$category, 'item_slug'=>$slug);
		$obj = new CategorizedItem($a);

		self::$sql->insert(self::$table_name, $obj, true);
	}
	/**
	* Delete a category, slug pair from the categorized_items table.
	* @param string $category String value of a category.
	* @param string $slug     A slug for an existing item in the my_items table.
	* @return void
	*/
	public static function delete(string $category, string $slug)
	{
		//print "<p>".__METHOD__."($category, $slug)</p>";
		//Category::delete($category);
		$query = "delete from categorized_items where category='$category' and item_slug='$slug'";
		$result = self::$sql->query($query);
		//var_dump($query);var_dump($result);
		//print "<p>".__METHOD__."</p>";
	}
	/**
	* Delete all category, slug pairs from the categorized_items table where slug == $slug.
	* @param string $slug A slug for an existing item in the my_items table.
	* @return \mysqli_result
	*/
	public static function delete_slug(string $slug)
	{
		$query = "delete from categorized_items where item_slug='$slug'";
		$result = self::$sql->query($query);
		return $result;
	}
}

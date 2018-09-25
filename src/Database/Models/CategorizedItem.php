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
	 * @param null $obj Dont know @todo findout.
	 */
	public function __construct($obj = null)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		// var_dump($obj);
		parent::__construct($obj);
	}
	/*!
	* Finds all the rows in the categorized_items table and returns them as an array of VOCategorizedItem objects
	* @param $count - can limit the number returned to the count value
	*/
	public static function find($count=NULL){
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
	/*!
	* Ensures a category, slug pair are in the categorized_items table. Insert of not there.
	* @param $category a string value of a category
	* @param $slug a slug for an existing item in the my_items table
	* @return void
	*/
	public static function add($category, $slug){
		\Trace::function_entry();
		$a = array('category'=>$category, 'item_slug'=>$slug);
		$obj = new CategorizedItem($a);

		self::$sql->insert(self::$table_name, $obj, true);
	}
	public static function delete($category, $slug){
		//print "<p>".__METHOD__."($category, $slug)</p>";
		//Category::delete($category);
		$query = "delete from categorized_items where category='$category' and item_slug='$slug'";
		$result = self::$sql->query($query);
		//var_dump($query);var_dump($result);
		//print "<p>".__METHOD__."</p>";
	}
	public static function delete_slug($slug){
		$query = "delete from categorized_items where item_slug='$slug'";
		$result = self::$sql->query($query);
		return $result;
	}
}

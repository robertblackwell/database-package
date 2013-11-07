<?php
namespace Database\Models;

/*!
** @ingroup Models
* This class represents a view of the items table that allows selection of a set of items by category
*/
class CategorizedItem extends Base\ModelBase
{
    static $table_name = "categorized_items";
    
    function __construct($row){
        parent::__construct($row);
    }
    /*!
    * Finds all the rows in the categorized_items table and returns them as an array of VOCategorizedItem objects
    * @param $count - can limit the number returned to the count value
    */
    static function find($count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "   order by category asc $count_str ";
        return self::$sql->select_objects("categorized_items", __CLASS__, $c);
    }
    /*!
    * Ensures a category, slug pair are in the categorized_items table. Insert of not there.
    * @param $category a string value of a category
    * @param $slug a slug for an existing item in the my_items table
    * @return void
    */
    static function add($category, $slug){
        //print "<p>".__METHOD__."($category, $slug)</p>";
        Category::add($category);
        $query = "insert into categorized_items(category, item_slug) values('$category', '$slug')";
        $result = self::$sql->query($query);
        if( !$result )
            throw new Exception(__CLASS__."::".__FUNCTION__." sql result false msg: ". mysql_error() );
        //var_dump($query);var_dump($result);
        //print "<p>".__METHOD__."</p>";
    }
    static function delete($category, $slug){
        //print "<p>".__METHOD__."($category, $slug)</p>";
        //Category::delete($category);
        $query = "delete from categorized_items where category='$category' and item_slug='$slug'";
        $result = self::$sql->query($query);
        //var_dump($query);var_dump($result);
        //print "<p>".__METHOD__."</p>";
    }
    static function delete_slug($slug){
        $query = "delete from categorized_items where item_slug='$slug'";
        $result = self::$sql->query($query);
        return $result;
    }

}

?>
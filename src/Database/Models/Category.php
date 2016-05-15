<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class is a standard Model but it is a little different in that
* it does not have a table hiding behind it but rather a view that uses a selection of all the distinct
* categorized_items(category) values. 
*/
class Category extends Base\ModelBase
{
    static $table_name = "categories";
    static $field_names = array(
        "category"=>"text",
		"slug" => "text",
		"trip" => "text"
        );
    function __construct($obj=null){       
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }


    static function find_for_trip($trip, $count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $q = "select distinct categorized_items.category, my_items.trip".
                " from categorized_items". 
                " inner join".
                    " my_items on categorized_items.item_slug = my_items.slug".
                    " where trip='".$trip."' ".
                    " order by category asc $count_str "; 
        $r = self::$sql->query_objects($q, __CLASS__ , true);
        return $r;
    }
    /*!
    * Find all the categories and return them in an array of Category objects
    * @note - in this incarnation of the package categories only exist in the 
    * categorized_items table and hence the categories table is actually a view
    * @param count - Limits the number returned
    * @return array of Category objects
    */
    static function find($count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $q = "select distinct categorized_items.category, my_items.trip".
                " from categorized_items ". 
                " inner join".
                    " my_items on categorized_items.item_slug = my_items.slug".
                    " order by category asc $count_str "; 
        $r = self::$sql->query_objects($q, __CLASS__, true);
        //var_dump($r);exit();
        return $r;
    }
    /*!
    ** Tests a string to see if it exists as a category in the categorized_items table
    */
    static function exists($category){
        $q = " where category = '".$category."'";
        $r = self::$sql->select_objects(self::$table_name, __CLASS__ ,$q, false);
        //var_dump($r);exit();
        return !is_null($r);
    }
    
//     /*!
//     * Ensures a category is in the category table. Insert if not there.
//     * (Actually insert and ignore the error of duplicate entry
//     * @param $category a string value of a category
//     * @return void
//     */
//     static function add($category){
//         //print "<p>".__METHOD__."($category)</p>";
//         // try to insert the object and ignore fails that means it is already there
//         $a = array('category'=>$category);
//         $obj = new category($a);
//         self::$sql->insert(self::$table_name, $obj, false);
//         return;
//         //print "<p>".__METHOD__."</p>";
//     }
//     /*!
//     * Ensures a category is NOT in the category table. Delete if there.
//     * (Actually insert and ignore the error of duplicate entry
//     * @param $category a string value of a category
//     * @return void
//     */
//     static function remove($category){
//         //print "<p>".__METHOD__."($category)</p>";
//         // try to insert the object and ignore fails that means it is already there
//         $a = array('category'=>$category);
//         $obj = new category($a);
//         self::$sql->delete(self::$table_name, $obj, false);
//         return;
//         //print "<p>".__METHOD__."</p>";
//     }

}

?>
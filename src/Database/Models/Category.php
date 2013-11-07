<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class is an active record for categories
*/
class Category extends Base\ModelBase
{
    static $table_name = "categories";
    static $field_names = array(
        "category"=>"text",
        );
    function __construct($obj=null){       
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }
    /*!
    * Find all the categories and return them in an array of VOCategory objects
    * @param count - Limits the number returned
    * @return array of VOCategory objects
    */
    static function find($count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = " order by category asc $count_str ";
        $r = self::$sql->select_objects(self::$table_name, __CLASS__ , $c);
        //var_dump($r);exit();
        return $r;
    }
    
    /*!
    * Ensures a category is in the category table. Insert if not there.
    * (Actually insert and ignore the error of duplicate entry
    * @param $category a string value of a category
    * @return void
    */
    static function add($category){
        //print "<p>".__METHOD__."($category)</p>";
        // try to insert the object and ignore fails that means it is already there
        $a = array('category'=>$category);
        $obj = new category($a);
        self::$sql->insert(self::$table_name, $obj, false);
        return;
        //print "<p>".__METHOD__."</p>";
    }

}

?>
<?php
namespace Database\Models;
/*!
** @ingroup XYModel
* This class is an active record for categories
*/
class Category extends Base\ModelBase
{
    static $table_name = "categories";
    
    function __construct($row){
        parent::__construct($row);
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
        $query = "insert into categories(category) values('$category')";
        $result = mysql_query($query);
        //var_dump($query);
        //var_dump($result);
        //print "<p>".__METHOD__."</p>";
    }

}

?>
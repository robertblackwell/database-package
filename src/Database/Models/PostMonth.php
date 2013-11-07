<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class represents a view of the item table that lists content items by month
* of publication
*/
class PostMonth extends Base\ModelBase
{
    static $table_name = "my_items";
    function __construct($row){
        parent::__construct($row);
    }
    /*!
    * Find all the months (yyy-mm) referenced by creation_date field of "post" or "entry" items in the my_items table
    * return them as an array of PostMonth objects
    * @param count Limits the number returned
    * @return array of PostMonth objects
    */ 
    static function find($count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT distinct year(creation_date) as `year`, month(creation_date) as `month` "
        ." FROM my_items WHERE (type='post' or type='entry')   order by creation_date desc";
        return self::$sql->query_objects($c, __CLASS__);
    }
}

?>
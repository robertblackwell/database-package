<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class provides access to journal entry by country and hence represents a view
*/
class EntryCountry extends Base\ModelBase
{
    static $table_name = "my_items";
    function __construct($obj){
        $this->vo_fields = array(
        );
        parent::__construct($obj);
    }
    static function find_for_trip($trip, $count=NULL){
        $where = " where trip='".$trip."' and type='entry' ";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT distinct country, trip FROM my_items $where order by country asc";
        return self::$sql->query_objects($c, __CLASS__);
    }
    /*!
    * Find all the countries referenced by "entry" items in the my_items table
    * return them as an array of EntryCountry objects
    * @param count Limits the number returned
    * @return array of EntryCountry objects
    */
    static function find($count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT distinct country FROM my_items WHERE type='entry'   order by country asc";
        return self::$sql->query_objects($c, __CLASS__);
    }

}

?>
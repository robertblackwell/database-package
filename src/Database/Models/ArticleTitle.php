<?php
namespace Database\Models;

/**
** This class provides a view of the my_items table that returns all the titles of items of type "article".
**
** @ingroup Models
*/
class ArticleTitle extends Base\Model
{
    // var year, month - see DAEntryMonth for confirmation of the attribute names
    function __construct($obj)
    {
        //print "<p>".__METHOD__."</p>";
        //var_dump($obj);
        parent::__construct($obj);
    }
    static function find_for_trip($trip, $count=NULL)
    {
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT slug, trip, title, country FROM my_items WHERE (trip = '".$trip."' and type='article') order by title asc";
        return self::$sql->query_objects($c, __CLASS__);
    }
    static function find($count=NULL)
    {
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT slug, title, country FROM my_items WHERE ( type='article')   order by title asc";
        return self::$sql->query_objects($c, __CLASS__);
    }

}

?>
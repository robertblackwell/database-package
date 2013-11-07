<?php
namespace Database\Models;
/*!
** @ingroup Models
* This is the Model class for fields/properties of a Journal Entry
* that are GPS locations related.
* BUT ONLY those fields held within the SQL table.
* There is no need to load the item file
*/
class EntryLocation extends Base\ModelBase
{
    function __construct($obj){
        $this->vo_fields = array(
            "trip"=>"text",
            "latitude"=>"text",
            "longitude"=>"text",
            "excerpt"=>"text",
            "published_date"=>"text",
            "country"=>"text",
            "place"=>"text",
        );
        parent::__construct($obj);
    }
    /*!
    * Find the locations data for all "entry" items for a particular trip in the my_items table
    * return them as an array of EntryLocation objects
    * @param $trip the trip code
    * @param count Limits the number returned
    * @return array of VOEntryCountry objects
    */
    static function find_for_trip($trip, $count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT slug, trip, title, excerpt, published_date, country, place, latitude, longitude FROM my_items WHERE (type='entry' and trip='".$trip."')   order by country asc";
        return self::$sql->query_objects($c, __CLASS__);
    }
    
}

?>
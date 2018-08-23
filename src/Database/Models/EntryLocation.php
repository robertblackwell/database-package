<?php
namespace Database\Models;
/*!
** @ingroup Models
* This is the Model class for fields/properties of a Journal Entry
* that are GPS locations related.
* BUT ONLY those fields held within the SQL table.
* There is no need to load the item file
*/
class EntryLocation extends ItemBase //Base\ModelBase
{
    static $table_name = "my_items";
    static $field_names = array(
        "version"=>"text",
		"slug"=>"text",
		"type"=>"text",
        "trip"=>"text",
        "vehicle" => "text",
        "status"=>"text",
        "creation_date"=>"date",
        "published_date"=>"date",
        "last_modified_date"=>"date",
        "miles"=>"text",
        "odometer"=>"text",
        "day_number"=>"text",
        "latitude"=>"text",
        "longitude"=>"text",
        "excerpt"=>"text",
        "country"=>"text",
        "place"=>"text",
		"content_ref"=>"text",
        "camping"=>"html",
        "has_camping"=>"has",

	);
    function __construct($obj){
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
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
        $c = "SELECT type, slug, trip, vehicle,
				excerpt, 
				published_date, 
				country, place, latitude, longitude 
				FROM my_items 
				WHERE ( (type='entry' OR type='location') and trip='".$trip."')   
				order by country asc";
        
		return self::$sql->query_objects($c, __CLASS__);
    }
    static function find_date_order($count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT type, slug, trip, vehicle,
                    miles, odometer, day_number, 
                    excerpt, 
                    published_date, 
                    country, place, latitude, longitude 
                    FROM my_items 
                    WHERE (type='entry' OR type='location')   
                    order by published_date asc";
        
        return self::$sql->query_objects($c, __CLASS__);
    }  

    static function find($count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = "SELECT type, slug, trip, vehicle,
					miles, odometer, day_number, 
					excerpt, 
					published_date, 
					country, place, latitude, longitude 
			        FROM my_items 
					WHERE (type='entry' OR type='location')   
					order by country asc";
        
		return self::$sql->query_objects($c, __CLASS__);
    }  
    function sql_insert(){
        \Trace::function_entry("");
        // Insert the item first otherwise a foreign key constraint will fail
        parent::sql_insert();    
        if( $this->has_camping ){
            \Trace::debug("adding camping to categorized_items");
            CategorizedItem::add("camping", $this->slug);
        }
        if( $this->has_border ){
            CategorizedItem::add("border", $this->slug);
        }
        \Trace::function_exit();
    }    
    function sql_delete(){
        //print "<p>".__METHOD__."</p>";
        if( $this->has_camping ){
            //print "<p>deleting camping</p>";
            CategorizedItem::delete("camping", $this->slug);
        //print "<p>".__METHOD__."camping </p>";
        }
        if( $this->has_border ){
            CategorizedItem::delete("border", $this->slug);
        //print "<p>".__METHOD__."border </p>";
        }
       parent::sql_delete();    
     }
	
	  
}

?>
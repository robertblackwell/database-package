<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class represents a journal entry
*/
class Entry extends ItemBase
{
    static $table_name = "my_items";
    static $field_names = array(
        "version"=>"text",
        "type"=>"text",
        "trip"=>"text",
        "slug"=>"text",
        "status"=>"text",
        "creation_date"=>"date",
        "published_date"=>"date",
        "last_modified_date"=>"date",
        //"entry_date"=>"date",
        "miles"=>"text",
        "odometer"=>"int",
        "day_number"=>"int",
        "place"=>"text",
        "country"=>"text",
        "latitude"=>"text",
        "longitude"=>"text",
        //"featured_image"=>"getter",        
        "featured_image"=>"text",        
        "title"=>"html",
        "abstract"=>"html",
        //"excerpt"=>"getter",
        "excerpt"=>"text",
        "main_content"=>"html",
        "camping"=>"html",
        "border"=>"html",
        "has_camping"=>"has",
        "has_border"=>"has",
        );
    function __construct($obj=null){       
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }
	static function find($count=null){
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = " where type='entry' order by last_modified_date desc $count_str ";
        return DataBase::getInstance()->select_objects(self::$table_name, __CLASS__, $c, true);
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
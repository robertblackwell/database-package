<?php
namespace Database\Models;
/*!
** @ingroup Models
* This class represents a post content item
*/
class Post extends ItemBase
{
    static $table_name = "my_items";
    static $field_names = array(
        "version"=>"text",
        "type"=>"text",
        "slug"=>"text",
        "status"=>"text",
        "creation_date"=>"date",
        "published_date"=>"date",
        "last_modified_date"=>"date",
        "trip"=>"text",
        "title"=>"html",
        "abstract"=>"html",
        "excerpt"=>"text",
        //"excerpt"=>"getter",
        "topic"=>"text",
        "tags"=>"list",
        "categories"=>"list",
        "featured_image"=>"text",        
        //"featured_image"=>"getter",        
        "main_content"=>"html",
        );    
    function __construct($obj=null){       
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        //print __CLASS__.":".__METHOD__.":";
        parent::__construct($obj);
    }
    /*!
    * Inserts the current content object. Has a custom insert to take care of categories
    */
    function sql_insert(){
        parent::sql_insert();
        if(!is_null($this->categories))
        foreach($this->categories as $cat){
            CategorizedItem::add($cat, $this->slug);
        }
    }
}
?>
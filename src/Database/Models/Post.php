<?php
namespace Database\Models;
/*!
** @ingroup ValueObjects
* This class is a value object for post style/type content
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
    * This returns the html (a single <p></p>) excerpt for this journal entry.
    */
    function excerpt(){
        return $this->get_first_p("main_content");
    }
    /*!
    * Returns the site relative URL (suitable for use in a <img src=> construct)
    * of the entries featured image. NULL is no featured image.
	*
	* The featured_image text should be a path relative to the post items content directory.
	* so for example gallery/Thumbnails/pict-3.jpg if the post had a gallery named "gallery"
	* @Note posts are not permitted to have a default gallery like entry items
	* @Note fr convenience the function adds a leading '/' if it not already there
	*
	* TODO An alternative for of the text is [galleryname, picture_index]
    */
    function featured_image(){
        $debug = false;
        $gal_img = $this->get_text('featured_image');
        if( substr($gal_img, 0, 1) != '/' ) $gal_img = "/".$gal_img;
        if( $debug ) print "<p>".__METHOD__." $gal_img</p>";
        if( trim($gal_img) == "")
            return null;
        $fn = DAConfig::item_dir($this->trip, $this->slug).$gal_img;
        if( $debug ) print "<p>".__METHOD__." $gal_img  fn: $fn</p>";
        if( is_file($fn) ){
            $url =  DAConfig::url_item_thumbnail($this->trip, $this->slug, null, $gal_img);
            if( $debug ) print "<p>".__METHOD__." $gal_img url: $url</p>";
            return DAConfig::url_item_thumbnail($this->trip, $this->slug, null, $gal_img);
        }else {
            if( $debug ) print "<p>file $fn does not exists</p>";
            return null;
        }
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
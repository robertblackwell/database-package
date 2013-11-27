<?php
namespace Database\Models;
use Database\HED\HEDObject;
use Database\Models\Factory;
use \Exception as Exception;
/*!
** @ingroup Models
* This class represents all types of content items (posts, entry, article) when there
* are a set of them retrieved and
* only summary data (stored in the sql database) is required.
*
* This class provides all static methods for finding individual or sets of content items
* 
* It also defines (from SQL tables) the set of fields/properties that are available is a 
* summary of a content item. 
**/
class Item extends ItemBase
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
        "featured_image"=>'text',
        "miles"=>"text",
        "odometer"=>"int",
        "day_number"=>"int",
        "place"=>"text",
        "country"=>"text",
        "latitude"=>"latitude",
        "longitude"=>"longitude",
        "camping"=>"html",
        );
    function __construct($obj){
        $this->vo_fields = array(
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
        "featured_image"=>'text',
        "miles"=>"text",
        "odometer"=>"int",
        "day_number"=>"int",
        "place"=>"text",
        "country"=>"text",
        "latitude"=>"latitude",
        "longitude"=>"longitude",
        "camping"=>"html",
        );
        parent::__construct($obj);
    }
    public static function get_by_trip_slug($trip, $slug){
        $obj = new HEDObject();
        $fn = self::$locator->item_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj);
        
        return $item;
    }
    /*!
    * Retrieve a content item by unique identifier (slug) and hence return one of
    * Post, Entry, Article
    */
    public static function get_by_slug($slug){
        $q = "WHERE slug='".$slug."'";
        $r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
        if( is_null($r) || !$r   ) return null;
        $trip = $r->trip;
               
        $obj = new HEDObject();
        $fn = self::$locator->item_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj);
        return $item;
    }
    public static function find($trip='rtw', $count=NULL){
        $where = " where trip='".$trip."'";
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = $where." order by published_date desc, slug desc $count_str ";
        return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
    }
    /*!
    * Finds the latest Items in reverse chronological order.
    * @param $typ optional - can limit the return to items of a specified type VOPost, VOEntry VOArticle
    * @param $count optional - can limit the number returned
    * @return array of objects of types VOEntry, VOPost, VOArticle
    */
    static function find_latest($count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = " order by last_modified_date desc, slug desc $count_str ";
        return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
    }
    /*!
    * Finds all the entry (Entry) items for the most recent  calendar month.
    * @param $count - optional can limit the number returned
    * @return void
    */
//     static function find_for_latest_month($count=null){
//         $mths = XYPostMonth::find();
//         //var_dump($mths);
//         $m = $mths[0]->month;
//         $y = $mths[0]->year;
//         //var_dump($m);
//         $ym = sprintf("%d%02d", (int)$y, (int)$m);
//         //var_dump($ym);
//         $a = self::find_for_month($y.$m, $count);
//         return $a;
//     }
    /*!
    * Finds all the entry (Entry) items for a given calendar month.
    * @param $year_month a string representing a calendar month in YYMM or YYYYMM format
    * @param $count - optional can limit the number returned
    * @return void
    */
    static function find_for_month($year_month, $count=NULL){
        //$klass = get_called_class();
        //$ty_str = ($klass == __CLASS__)? " ":  type="\"". strtolower(substr($klass,2)) ."\"";
        $start = $year_month."-01";
        $end =  $year_month."-99";
        //var_dump($start);var_dump($end);
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = " WHERE (type='entry' or type = 'post') and ".
        " (published_date >= \"$start\" ) and ".
        " (published_date <=\"$end\") ".
        " order by published_date asc, slug asc $count_str ";
        //var_dump($c);
        $res = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
        return $res;
    }
    /*!
    * Finds all the entry (Entry) items for a given country.
    * @param $country a string representing a country
    * @param $count - optional can limit the number returned
    * @return void
    */
    static function find_for_country($country, $count=NULL){
        $count_str = ($count)? "limit 0, $count": "" ;
        $c = " WHERE  (type='entry' or type = 'post')  and country = \"$country\" "
        . " order by published_date asc,  slug asc $count_str ";
        return self::$sql->select_objects(self::$table_name, __CLASS__, $c);
    }
    /*!
    * Finds all journal entries with camping comments for a given country
    * @parms $country
    * @return Array of Entry objects
    */
    static function find_camping_for_country($country){
        //print "<p>".__METHOD__."</p>";
        $where = " where b.category = 'camping' and a.country='$country'" ;
        $query = 
        "select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
            ."$where "
            ." order by published_date asc, slug asc ";
        //var_dump($query);
        $r = self::$sql->query_objects($query, __CLASS__);
        //var_dump($r);
        //exit();
        return $r;
    }
    /*!
    * Adds a category to a VOItem or adds a VOItem to a category.
    * @param $item VOItem object
    * @param $category String
    * @return array of VO objects
    */
    static function find_for_category($category=null, $count=NULL){
        //print "<p>".__METHOD__."</p>";
        $count_str = ($count)? "limit 0, $count": "" ;
        $category_str = ($category)? " where b.category = '$category' ": " " ;
        $query = 
        "select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
            ."$category_str "
            ." order by published_date asc, slug asc $count_str;";
        //var_dump($query);
        $r = self::$sql->query_objects($query, __CLASS__);
        //var_dump($r);
        //exit();
        return $r;
    }
}   
?>
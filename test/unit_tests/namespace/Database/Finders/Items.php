<?php
namespace Database\Finders;
/*!
** @ingroup Database_Finders
**/
class Items 
{
		function __construct(){
			print "constructor for ".__CLASS__." called \n";
		}
	    /*!
	    * Retrieve a content item by unique identifier (slug) and hence return one of
	    * XYPost, XYEntry, XYArticle
	    */
	    function getBySlug($slug){
	        $query = "select trip from my_items where slug='".$slug."'";
	        $result = DataBase::getInstance()->query($query);
	        $a = mysql_fetch_assoc($result);
	        //var_dump($a);
	        if( is_null($a) || !$a || (count($a) != 1)){
	            throw new Exception(__METHOD__." result is null or count(result) != 1");
	        }
	        //$a = mysql_fetch_assoc($result);
	        //var_dump($a);
	        $obj = new HEDWrap();
	        $obj->load($a['trip'], $slug);
	        //var_dump($obj);
	        $xy_obj = XYFactory::from_HEDWrap($obj);
	        return $xy_obj;
	    }
		/*!
	    * Finds the latest VO\Items in reverse chronological order.
	    * @param $count optional - can limit the number returned
	    * @return array of objects of types VOEntry, VOPost, VOArticle
		* 
		*/
	    function find($count=NULL){
	        $count_str = ($count)? "limit 0, $count": "" ;
	        $c = " order by published_date desc, slug desc $count_str ";
	        return DataBase::getInstance()->select_objects(self::$table_name, __CLASS__, $c, true);
	    }
	    /*!
	    * Finds the latest VOItems in reverse chronological order.
	    * @param $typ optional - can limit the return to items of a specified type VOPost, VOEntry VOArticle
	    * @param $count optional - can limit the number returned
	    * @return array of objects of types VOEntry, VOPost, VOArticle
	    */
	    function find_latest($count=NULL){
	        $count_str = ($count)? "limit 0, $count": "" ;
	        $c = " order by last_modified_date desc, slug desc $count_str ";
	        return DataBase::getInstance()->select_objects(self::$table_name, __CLASS__, $c, true);
	    }
	    /*!
	    * Finds all the entry (VOEntry) items for the most recent  calendar month.
	    * @param $count - optional can limit the number returned
	    * @return void
	    */
	    function find_for_latest_month($count=null){
	        $mths = XYPostMonth::find();
	        //var_dump($mths);
	        $m = $mths[0]->month;
	        $y = $mths[0]->year;
	        //var_dump($m);
	        $ym = sprintf("%d%02d", (int)$y, (int)$m);
	        //var_dump($ym);
	        $a = self::find_for_month($y.$m, $count);
	        return $a;
	    }
	    /*!
	    * Finds all the entry (VOEntry) items for a given calendar month.
	    * @param $year_month a string representing a calendar month in YYMM or YYYYMM format
	    * @param $count - optional can limit the number returned
	    * @return void
	    */
	    function find_for_month($year_month, $count=NULL){
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
	        $res = DataBase::getInstance()->select_objects(self::$table_name, __CLASS__, $c);
	        return $res;
	    }
	    /*!
	    * Finds all the entry (VOEntry) items for a given country.
	    * @param $country a string representing a country
	    * @param $count - optional can limit the number returned
	    * @return void
	    */
	    function find_for_country($country, $count=NULL){
	        $count_str = ($count)? "limit 0, $count": "" ;
	        $c = " WHERE  (type='entry' or type = 'post')  and country = \"$country\" "
	        . " order by published_date asc,  slug asc $count_str ";
	        return DataBase::getInstance()->select_objects(self::$table_name, __CLASS__, $c);
	    }
	    /*!
	    * Adds a category to a VOItem or adds a VOItem to a category.
	    * @param $item VOItem object
	    * @param $category String
	    * @return void
	    */
	    function find_for_category($category=null, $count=NULL){
	        //print "<p>".__METHOD__."</p>";
	        $count_str = ($count)? "limit 0, $count": "" ;
	        $category_str = ($category)? " where b.category = '$category' ": " " ;
	        $query = 
	        "select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
	            ."$category_str "
	            ." order by published_date asc, slug asc $count_str;";
	        //var_dump($query);
	        $r = DataBase::getInstance()->query_objects($query, __CLASS__);
	        //var_dump($r);
	        //exit();
	        return $r;
	    }		
}   
?>
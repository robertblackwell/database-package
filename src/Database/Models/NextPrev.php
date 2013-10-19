<?php
namespace Database\Models;

/*!
** @ingroup Model
** This class is an iterator that knows how to step through
** various sequences of Items with next/prev verbs.
**
*/
/*!
*/
class QueryMaker{
    function query($join, $where, $after_before, $order_by){
    }
}
/*!
** The intent is that each of Entry, Article, Post will have an iterator
** as a delegate. This will be provided by ItemBase the abstract base class
**
** The iterator only returns an instance of Item or null 
*/
class NextPrev{
    var $order_by_asc_with_limit = " order by published_date asc, slug asc limit 0, 1  ";
    var $join_order_by_asc_with_limit = " order by a.published_date asc, a.slug asc limit 0, 1  ";

    var $order_by_desc_with_limit = " order by published_date desc, slug desc limit 0, 1  ";
    var $join_order_by_desc_with_limit = " order by a.published_date desc, a.slug desc limit 0, 1  ";

    function join_after(){
        $s = "( "
            . " ( a.published_date > '" . $this->_obj->published_date."')"
            . " or ( a.published_date = '". $this->_obj->published_date. "' and a.slug > '".$this->_obj->slug."' ) "
            . ")";
        return $s;
    }
    function after(){
        $s = "( "
            . " ( published_date > '" . $this->_obj->published_date."')"
            . " or ( published_date = '". $this->_obj->published_date. "' and slug > '".$this->_obj->slug."' ) "
            . ")";
        return $s;
    }
    function join_before(){
        $s = "( "
            . " ( a.published_date < '" . $this->_obj->published_date."')"
            . " or ( a.published_date = '". $this->_obj->published_date. "' and a.slug<'".$this->_obj->slug."' )"
            ." ) ";
        return $s;
    }
    function before(){
        $s = "( "
            . " ( published_date < '" . $this->_obj->published_date."')"
            . " or ( published_date = '". $this->_obj->published_date. "'  and  slug < '".$this->_obj->slug."' )"
            ." ) ";
        return $s;
    }
    var $_obj;
    /*
    ** Constructs the iterator for one of Entry, Article, Post
    */
    function __construct($xyObj, $sql){
        $this->_obj = $xyObj;
        $this->sql = $sql;
    }
    /*!
    * Finds the next entry within all the entries for a country.
    * @param $country a string representing a country
    * @return Entry
    */
    function next_within_country($country, $class='\Database\Models\Item'){
        $country_str = ($country!=null)
            ? " where country = '$country' and type='entry' "
            : " where type='entry'  " ;
        $query = 
            "select * from my_items "
                ." $country_str and "
                . $this->after()
                . $this->order_by_asc_with_limit;
        //var_dump($query);    
        $r =  $this->sql->query_objects($query,  $class, false);
        //var_dump($r);
        return $r;
    }
    /*!
    * Finds the previous entry within all the entries for a country.
    * @param $country a string representing a country
    * @return Entry
    */
    function prev_within_country($country, $class='\Database\Models\Item'){
        $country_str = ($country!=null)
            ? " where country = '$country' and type='entry' "
            : " where type='entry'  " ;
        $query = 
            "select * from my_items "
                ." $country_str and "
                . $this->before()
                . $this->order_by_desc_with_limit;
        //var_dump($query);    
        $r =  $this->sql->query_objects($query,  $class, false);
        //var_dump($r);
        return $r;
    }
    function next_within_month($month){
        if( !is_null( $month ) ){
            $start = $month.'-01';
            $end = $month.'-31';
            $where_str = "where (published_date between '".$start."' and '".$end."' )";
        }else{
            $where_str = '';
        }
        $query = 
        "select * from my_items  "
            ." $where_str and "
            . $this->after()
            . $this->order_by_asc_with_limit;
        
        $r =  $this->sql->query_objects($query,  '\Database\Models\Item', false);
        //print "<p>next query $query</p>";
        //var_dump($r);
        return $r;
    }
    function prev_within_month($month, $class='\Database\Models\Item'){
        
        if( !is_null( $month ) ){
            $start = $month.'-01';
            $end = $month.'-31';
            $where_str = "where (published_date between '".$start."' and '".$end."' )";
        }else{
            $where_str = '';
        }

        $query = 
            "select * from my_items  "
                ." $where_str and "
                . $this->before()
                . $this->order_by_desc_with_limit;
        
        $r =  $this->sql->query_objects($query,  $class, false);
        //print "<p>prev query $query</p>";
        //var_dump($r);
        return $r;
    }
    /*
    ** Category navigation methods
    */
    function next_within_category($category=null, $class= '\Database\Models\Item'){
        $category_str = ($category) ? " where ( b.category = '$category' ) and"
                                    : " where " ;
        $query = 
            "select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
                ." $category_str "
                . $this->join_after()
                . $this->join_order_by_asc_with_limit;
        
        $r =  $this->sql->query_objects($query,  $class, false);
        return $r;
    }
    function prev_within_category($category=null, $class= '\Database\Models\Item'){
        $category_str = ($category) ? " where ( b.category = '$category' ) and "
                                    : " where " ;
        $query = 
        "select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
            ." $category_str "
            . $this->join_before()
            . $this->join_order_by_desc_with_limit;
            
        $r =  $this->sql->query_objects($query, $class, false);
        return $r;
    }
    /*
    ** Gets the next post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return Post object   
    */
    function next($criteria=null, $class= '\Database\Models\Item'){
        if( ($criteria != null ) ){
            if( !is_array($criteria) ) {
                $c = (is_object($criteria)? get_class($criteria): gettype($criteria));
                throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not array is: ".$c);
            }
            if( count($criteria)  == 1 ){
                if( array_key_exists('category', $criteria) ) {
                    $category = $criteria['category'];
                    return $this->next_within_category($category,$class);
                } else if( array_key_exists('country', $criteria) ) {
                    $country = $criteria['country'];
                    return $this->next_within_country($country,$class);
                } else if( array_key_exists('months', $criteria) ) {
                    $month = $criteria['months'];
                    return $this->next_within_month($month, $class);
                }else{
                    throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not category is: "
                    . print_r($criteria, true));
                }
            }else{
                throw new \Exception(__CLASS__.'::'.__FUNCTION__." too many/few : "
                    . count($criteria) ."] array elements : "
                    . print_r($criteria, true));
            }
        }
        // There is not criteria so do a "natural order" next
        $query = "select * from my_items WHERE "
                . $this->after()
                . $this->order_by_asc_with_limit;
        
        $r = $this->sql->query_objects($query, $class,  false);
        //print "<p>next: $query</p>";var_dump($r->slug);
        return $r;
    }
    /*
    ** Gets the prev post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return Post object   
    */
    function prev($criteria=null, $class='\Database\Models\Item'){
        if( ($criteria != null ) ){
            if( !is_array($criteria) ) {
                $c = (is_object($criteria)? get_class($criteria): gettype($criteria));
                throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not array is: ".$c);
            }
            if( count($criteria)  == 1 ){
                if( array_key_exists('category', $criteria) ) {
                    $category = $criteria['category'];
                    return $this->prev_within_category($category, $class);
                } else if( array_key_exists('country', $criteria) ) {
                    $country = $criteria['country'];
                    return $this->prev_within_country($country, $class);
                } else if( array_key_exists('months', $criteria) ) {
                    $month = $criteria['months'];
                    return $this->prev_within_month($month, $class);
                }else{
                    throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not category is: "
                    . print_r($criteria, true));
                }
            }else{
                throw new \Exception(__CLASS__.'::'.__FUNCTION__." too many/few["
                    . count($criteria) ."]array elements : "
                    . print_r($criteria, true));
            }
        }
        $query = "select * from my_items WHERE "
                . $this->before()
                . $this->order_by_desc_with_limit;
        $r = $this->sql->query_objects($query, $class,  false);
        //print "<p>prev: $query</p>";var_dump($r->slug);
        return $r;
    }    
}
?>
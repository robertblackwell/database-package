<?php
namespace Database\Models;

use \Database\SqlObject;

/**
** This class provides an iterator to move through next/prev
** different sets of content items. The intent is that each of Entry, Article, Post will have an iterator
** as a delegate. This will be provided by ItemBase the abstract base class
**
** The iterator only returns an instance of Item or null.
*/
class NextPrev extends \stdClass
{
	/**
	* @var Entry|Post|Article $obj The object for which this class is finding the next or prev.
	*/
	private $obj;

	private $order_by_asc_with_limit = " order by published_date asc, slug asc limit 0, 1  ";
	private $join_order_by_asc_with_limit = " order by a.published_date asc, a.slug asc limit 0, 1  ";

	private $order_by_desc_with_limit = " order by published_date desc, slug desc limit 0, 1  ";
	private $join_order_by_desc_with_limit = " order by a.published_date desc, a.slug desc limit 0, 1  ";
	/**
	* Creates part of an sql query string that finds, via a join, the next slug after
	* the current ordered by published_date.
	* @return sttring
	*/
	protected function join_after() : string
	{
		$s = "( "
			. " ( a.published_date > '" . $this->_obj->published_date."')"
			. " or ( a.published_date = '". $this->_obj->published_date. "' and a.slug > '".$this->_obj->slug."' ) "
			. ")";
		return $s;
	}
	/**
	* Return part an Sql query string that will find the next slug
	* after the current one ordered by published date.
	* @return string
	*/
	protected function after() : string
	{
		$s = "( "
			. " ( published_date > '" . $this->_obj->published_date."')"
			. " or ( published_date = '". $this->_obj->published_date. "' and slug > '".$this->_obj->slug."' ) "
			. ")";
		return $s;
	}
	/**
	* Return an Sql query string that will find the slug immediately before the current one ordered by
	* published date.
	* @return string
	*/
	protected function join_before() : string
	{
		$s = "( "
			. " ( a.published_date < '" . $this->_obj->published_date."')"
			. " or ( a.published_date = '". $this->_obj->published_date. "' and a.slug<'".$this->_obj->slug."' )"
			." ) ";
		return $s;
	}
	/**
	* Return an Sql query string that will find the slug immediately before the current one
	* ordered by published date.
	* @return string
	*/
	protected function before() : string
	{
		$s = "( "
			. " ( published_date < '" . $this->_obj->published_date."')"
			. " or ( published_date = '". $this->_obj->published_date. "'  and  slug < '".$this->_obj->slug."' )"
			." ) ";
		return $s;
	}
	/**
	* Constructs the iterator for one of Entry, Article, Post
	* @param Entry|Article|Post $xyObj The object for which a next/prev iterator is to be constructed.
	* @param SqlObject          $sql   An sql object to be used by the iterator.
	* @return NextPrev
	*/
	public function __construct($xyObj, SqlObject $sql)
	{
		$this->_obj = $xyObj;
		$this->sql = $sql;
	}
	/**
	* Finds the next entry within all the entries for a country.
	* @param string $country A string representing a country.
	* @param string $class   Name of the class to instantiate.
	* @return Entry | Post | null
	*/
	public function next_within_country(string $country, string $class = '\Database\Models\Item')
	{
		$country_str = ($country!=null)
			? " where country = '$country' and type='entry' "
			: " where type='entry'  " ;
		$query =
			"select * from my_items "
				." $country_str and "
				. $this->after()
				. $this->order_by_asc_with_limit;
		//var_dump($query);
		$r =  $this->sql->query_objects($query, $class, false);
		//var_dump($r);
		return $r;
	}
	/**
	* Finds the previous entry within all the entries for a country.
	* @param string $country A name or code for a country.
	* @param string $class   A class name. The returned value will be of this class.
	* @return Entry|Post|Article
	*/
	public function prev_within_country(string $country, string $class = '\Database\Models\Item')
	{
		$country_str = ($country!=null)
			? " where country = '$country' and type='entry' "
			: " where type='entry'  " ;
		$query =
			"select * from my_items "
				." $country_str and "
				. $this->before()
				. $this->order_by_desc_with_limit;
		//var_dump($query);
		$r =  $this->sql->query_objects($query, $class, false);
		//var_dump($r);
		return $r;
	}
	/**
	* Finds the next entry within all the entries for a month.
	* @param string $month A month mmyy format.
	* @param string $class A class name. The returned value will be of this class.
	* @return Entry|Post|Article|null
	*/
	public function next_within_month(string $month, string $class = '\Database\Models\Item')
	{
		return $this->next_absolute($class);
	}
	/**
	* Finds the prev entry within all the entries for a month.
	* @param string $month A month mmyy format.
	* @param string $class A class name. The returned value will be of this class.
	* @return Entry|Post|Article|null
	*/
	public function prev_within_month(string $month, string $class = '\Database\Models\Item')
	{
		return $this->prev_absolute($class);
	}
	/**
	* Finds the next entry within all the entries for a category.
	* @param string|null $category A category or null.
	* @param string      $class    A class name. The returned value will be of this class.
	* @return Entry|Post|Article|null
	*/
	public function next_within_category(string $category = null, string $class = '\Database\Models\Item')
	{
		$category_str = ($category) ? " where ( b.category = '$category' ) and"
									: " where " ;
		$query =
			"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
				." $category_str "
				. $this->join_after()
				. $this->join_order_by_asc_with_limit;
		
		$r =  $this->sql->query_objects($query, $class, false);
		return $r;
	}
	/**
	* Finds the prev entry within all the entries for a category.
	* @param string|null $category A category or null.
	* @param string      $class    A class name. The returned value will be of this class.
	* @return Entry|Post|Article|null
	*/
	public function prev_within_category(string $category = null, string $class = '\Database\Models\Item')
	{
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
	/**
	* Find the next item amongst ALL items ordered by published_date.
	* @param string $class A class name of the type of object to be returned.
	* @return Entry|Post|Article|null
	*/
	public function next_absolute(string $class)
	{
		// There is not criteria so do a "natural order" next
		$query = "select * from my_items WHERE "
				. $this->after()
				. $this->order_by_asc_with_limit;
		
		$r = $this->sql->query_objects($query, $class, false);
		//print "<p>next: $query</p>";var_dump($r->slug);
		return $r;
	}
	/**
	* Find the prev item amongst ALL items ordered by published_date.
	* @param string $class A class name of the type of object to be returned.
	* @return Entry|Post|Article|null
	*/
	public function prev_absolute(string $class)
	{
		$query = "select * from my_items WHERE "
			. $this->before()
			. $this->order_by_desc_with_limit;
		$r = $this->sql->query_objects($query, $class, false);
		//print "<p>prev: $query</p>";var_dump($r->slug);
		return $r;
	}

	/**
	* Gets the next post in order based on the criteria.
	* The only valid criteria is a  'category'
	* @param array|null $criteria The criteria to be used.
	* @param string     $class    A class name of the type of object to be returned.
	* @return Post|Entry|Article|null
	* @throws \Exception Criteria invalid.
	*/
	public function next($criteria = null, string $class = '\Database\Models\Item')
	{
		if (($criteria != null)) {
			if (!is_array($criteria)) {
				$c = (is_object($criteria)? get_class($criteria): gettype($criteria));
				throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not array is: ".$c);
			}
			if (count($criteria)  == 1) {
				if (array_key_exists('category', $criteria)) {
					$category = $criteria['category'];
					return $this->next_within_category($category, $class);
				} elseif (array_key_exists('country', $criteria)) {
					$country = $criteria['country'];
					return $this->next_within_country($country, $class);
				} elseif (array_key_exists('months', $criteria)) {
					$month = $criteria['months'];
					return $this->next_within_month($month, $class);
				} else {
					throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not category is: "
					. print_r($criteria, true));
				}
			} else {
				throw new \Exception(__CLASS__.'::'.__FUNCTION__." too many/few : "
					. count($criteria) ."] array elements : "
					. print_r($criteria, true));
			}
		}
		return $this->next_absolute($class);
	}
	/**
	* Gets the prev post in order based on the criteria.
	* The only valid criteria is a  'category'.
	* @param null|array $criteria The criteria.
	* @param string     $class    A class name of the type of object to be returned.
	* @return Post|Entry|Article|null
	* @throws \Exception Criteria invalid.
	*/
	public function prev($criteria = null, string $class = '\Database\Models\Item')
	{
		if (($criteria != null)) {
			if (!is_array($criteria)) {
				$c = (is_object($criteria)? get_class($criteria): gettype($criteria));
				throw new \Exception(__CLASS__.'::'.__FUNCTION__." criteria is not array is: ".$c);
			}
			if (count($criteria)  == 1) {
				if (array_key_exists('category', $criteria)) {
					$category = $criteria['category'];
					return $this->prev_within_category($category, $class);
				} elseif (array_key_exists('country', $criteria)) {
					$country = $criteria['country'];
					return $this->prev_within_country($country, $class);
				} elseif (array_key_exists('months', $criteria)) {
					$month = $criteria['months'];
					return $this->prev_within_month($month, $class);
				} else {
					throw new \Exception(
						__CLASS__.'::'.__FUNCTION__." criteria is not category is: "
						. print_r($criteria, true)
					);
				}
			} else {
				throw new \Exception(__CLASS__.'::'.__FUNCTION__." too many/few["
					. count($criteria) ."]array elements : "
					. print_r($criteria, true));
			}
		}
		return $this->prev_absolute($class);
	}
}

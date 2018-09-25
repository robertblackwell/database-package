<?php
namespace Database\Models;
/**
* This class represents content that is an article.
* It provides static methods for accessing collections of articles.
*
* @ingroup Models
*/
class Article extends ItemBase
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
		"main_content"=>"include",
		"featured_image"=>"text",
		//"featured_image"=>"getter",
		"content_path" => "text",
		"entity_path" => "text"
		);
	function __construct($obj=null){
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		parent::__construct($obj);
	}
	static function find_for_trip($trip, $count=NULL){
		$where = " where ( trip='".$trip."' and type = 'article' )";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " $where order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__ , $c);
		//var_dump($r);exit();
		return $r;
	}
	/*!
	* Find all the articles and return them in an array of VOCategory objects
	* @param count - Limits the number returned
	* @return array of VOCategory objects
	*/
	static function find($count=NULL){
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type='article' order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__ , $c);
		//var_dump($r);exit();
		return $r;
	}
	/*!
	* This returns the html (a single <p></p>) excerpt for this journal entry.
	*/
	function excerpt(){
		return $this->abstract;
	}
}

?>
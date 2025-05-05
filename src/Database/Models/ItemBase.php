<?php
namespace Database\Models;

use Database\Models\NextPrev;
use Database\Models\Model;

/*!
** @ingroup Models
* This is the base class for those objects that represent content items such as
* post, entry or article. One of the reasons for this is to have a common next/prev
* object included as a property
*/
class ItemBase extends Model
{
	protected $next_prev;
	/**
	* Constructor.
	* @param mixed $obj Sql query result as associatibe array, or HEDOBject.
	* @return Item
	*/
	public function __construct($obj)
	{
		$this->next_prev = new NextPrev($this, self::$sql);
	}
	/**
	* Deletes this instance from the sql database.
	* @return void
	*/
	public function sql_delete()
	{
		self::$sql->delete($this->table, $this);
		CategorizedItem::delete_slug($this->slug);
	}
	/**
	* Gets the next Item in order based on the criteria.
	* The only valid criteria is a  'category'
	* @param array  $criteria Such as ('category'=> some category).
	* @param string $class    The class of item to be returned.
	* @return Item | null
	*/
	public function next(?array $criteria = null, string $class = '\Database\Models\Item')
	{
		return $this->next_prev->next($criteria, $class);
	}
	/**
	* Gets the prev Item in order based on the criteria.
	* The only valid criteria is a  'category'
	* @param array  $criteria Such as ('category'=> some category).
	* @param string $class    The class of item to be returned.
	* @return Item | null
	*/
	public function prev(?array $criteria = null, string $class = '\Database\Models\Item')
	{
		return $this->next_prev->prev($criteria, $class);
	}
}

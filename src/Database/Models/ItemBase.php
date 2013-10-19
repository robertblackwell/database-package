<?php
namespace Database\Models;
use Database\Models\NextPrev;
/*!
** @ingroup Models
* This is the base class for those objects that represent content items such as
* post, entry or article. One of the reasons for this is to have a common next/prev
* object included as a property
*/
class ItemBase extends Base\ModelBase 
{
    function __construct($obj){
        parent::__construct($obj);
        $this->next_prev = new NextPrev($this, self::$sql);
    }
    /*!
    * Deletes the properties of this object from the  table of the
    * sql database.
    */
    function sql_delete(){
        self::$sql->delete($this->table, $this);
        CategorizedItem::delete_slug($this->slug);
    }
    /*
    ** Gets the next post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return XYPost object   
    */
    function next($criteria=null, $class= '\Database\Models\Item'){
        return $this->next_prev->next($criteria, $class);
    }
    /*
    ** Gets the prev post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return XYPost object   
    */
    function prev($criteria=null, $class='\Database\Models\Item'){
        return $this->next_prev->prev($criteria, $class);
    }    
}
?>
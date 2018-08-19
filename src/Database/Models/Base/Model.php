<?php
namespace Database\Models\Base;
use Database\Models\CategorizedItem;
use \Exception as Exception;

/*!
** @defgroup Models
** Models are classes that represent entities in the database and are defined to suite the application
** domain.
**
** Each model should be derived from the class ModelBase
*/

/*!
** @ingroup Models
**  This is the base class for all model classes.
**  It is the first place in the class hierarchy that knows about field names
**
*/
class Model extends Row
{
    /**
    * @var $sql \Database\SqlObject - used for models to make sql calls. Set up during initialization
    */
    static $sql;
    /**
    * @var $locator \Database\Locator - used to locate data entities in the flat file system. Set up during
    * initialization
    */
    static $locator;

    /**
    * @var $table string - the name of the sql table/view this model is connected to. Each derived class
    * MUST provide a value for this property
    */
    protected $table;     //name of the corresponding SQL table
    
    function __construct($obj)
    {
        //print __CLASS__.":".__METHOD__.":";
        parent::__construct($obj);
    }

    /*
    ** Below here are a set of common "finder" functions
    */
    static function getBySlug($slug)
    {
    }
    static function findWhere($where)
    {
        $c = $where;
        return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
    } 
       
    /* 
    ** Below here are a small set of standard sql operations
    */
    /*!
    * Inserts the relevant properties of this object into the  table of the
    * sql database.
    */
    function sql_insert()
    {
        //print "<p>".__METHOD__."</p>\n";
        self::$sql->insert($this->table, $this);
        //print "<p>".__METHOD__."</p>\n";
    }
    /*!
    * Updates the relevant properties of this object into the table of the
    * sql database.
    */
    function sql_update()
    {
        self::$sql->update($this->table, $this);
    }
    /*!
    * Deletes the properties of this object from the  table of the
    * sql database.
    */
    function sql_delete()
    {
        self::$sql->delete($this->table, $this);
        CategorizedItem::delete_slug($this->slug);
    }
    
}
?>
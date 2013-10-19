<?php
namespace Autoloader;

class Loader{
	var $dir_list;
	
//	static private var me;
	
	function __construct($directory = null){
//		self::$me = $this; //ensure the autoloader does not get garbage collected
		$this->dir_list=array();
		if(!is_null($directory)) $this->dir_list[] = $directory;
	    spl_autoload_register(array($this, '_autoloader'));	
	}
	function add_dir($dir){
		$this->dir_list[] = $dir;
	}
    /*!
    *   Autoloader using spl so that 3rd party packages like Smarty can use their own loader.
    *   Looks in framework, controllers, models, classes  for files to load.
    *   Some classes are treated in a special way because their names do not follow standard
    *   The pear naming convention is used within the classes directory
    *
    *   Files are always   strtolower(classname).php
    *   @param $class_name
    *   @return nothing
    *   @throws a runtie exception if class not found
    */
    private function _autoloader($class_name){
        $debug=false;
        if( $debug) print "<h1>".__METHOD__."(class name $class_name) entry</h1>\n";
		$lower_class_name = $class_name;//strtolower($class_name);
        $cn = $lower_class_name;
        $dir_list = $this->dir_list;
		try{
	            if (false !== $pos = strrpos($lower_class_name, '\\')) {
	                //print "<h1>autoloader PEAR class $class_name</h1>";
	                $cn = str_replace('\\', '/', $lower_class_name);
	                if( $debug) print "<h1>autoloader namespace class $class_name cn: $cn</h1>\n";
	            } else if (false !== $pos = strrpos($lower_class_name, '_')) {
	                //print "<h1>autoloader PEAR class $class_name</h1>";
	                $cn = str_replace('_', '/', $lower_class_name);
	                if( $debug) print "<h1>autoloader PEAR class $class_name cn: $cn</h1>\n";
	            }
	            foreach($dir_list as $d){
	                $fn = $d."/".$cn.".php";
	                if( $debug) print "<p>root:  [$d]  [$cn]  [$fn] exists: ". (int)file_exists($fn)  ."</p>\n";
	                if( file_exists($fn )){
	                	if($debug) print "<h1>GOT IT $fn</h1>\n";
	                	require($fn);
	                	if($debug) print "<h1>GOT IT $fn</h1>\n";
	                    if( $debug) print "<p>".__METHOD__." got it file: $fn  class: $class_name </p>\n";
	                    if(!class_exists($class_name) ){
                            if( $debug) print "<h3>".__METHOD__."(class name $class_name) DOES NOT exists in $d   exit</h3>\n";
	                    }else{
                            if( $debug) print "<h3>".__METHOD__."(class name $class_name) exists exit</h3>";
	                    }
                        if( $debug) print "<h3>".__METHOD__."(class name $class_name) exit</h3>";
	                    return;
	                }else{
		
					}
	            }
		} catch( Exception $e ){
			//print "<h1>Autoloader error</h1>";
			//var_dump($e);
			throw new \Exception($e->getMessage());
		}
		
        if( $debug) print "<h1>".__METHOD__."(class name $class_name) exit </h1>\n";
        //throw new Exception("autoloader: class $class_name not found");
    }

}
?>
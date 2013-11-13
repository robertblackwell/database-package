<?php
/*!
* This is a config object. One gets created at initialization and a hook to
* that object is placed in the Registry object
*/
class ConfigObject
{
    var $server_name;
    var $doc_root;
    var $system_mode;
    var $host_name;
    var $uri_name;
    var $url_root;
    var $request_method;
    var $query_string;
    var $script_filename;
    var $app_name;
    var $data_root;         /// Full path to the root of the data directory
    var $php_root;          /// Full path to the root of the php code directory
    var $temporary_root;    /// Full path to the root of the temporary file directory
    var $skins_root;        /// Full path to the root of the skins directory
    var $content_root;      /// Full path to the directory containing content items for new database 
    var $articles_dir;      /// @Deprecated
    var $vers ;             /// System version number
    var $db;                /// Database config
    var $app_route;         /// Application url routing config table
    var $autoloader_table;  /// Autoloader search table
    var $config_path;		/// the path from which we should load config files
    var $logger_root;       /// the path to the directory in which things are logged
    var $xroot;

    function __construct($root, $where=null, $mode=null){

        $this->xroot = $root;
//        var_dump(realpath($root));exit();
//        var_dump(dirname(__FILE__));
//        var_dump($_SERVER);
		if( !is_null($where) ){
			//print "<h3>constructor $where not null</h3>";
			$this->config_path = $where;
		}else{
			$this->config_path = null;
		}
/*		if( defined($_SERVER) ){
        if( is_null($mode)){
            $this->doc_root_and_mode($_SERVER);
        }else{
            $this->system_mode = $mode;
            throw new Exception(__METHOD__." dont yet know how to handle explicit system mode");
        }

        $this->server_name = $_SERVER['SERVER_NAME'];  
        $this->host_name = $_SERVER['HTTP_HOST'];
        $this->uri_name  = $_SERVER['REQUEST_URI'];
        $this->url_root  = "http://".$this->host_name;
        $this->url_data_root  = "/data";
        $this->url_skins_root  = "/skins";
        /// Request related info
        $this->request_method  = $_SERVER["REQUEST_METHOD"];
        $this->query_string    = $_SERVER["QUERY_STRING"];
        $this->script_filename = $_SERVER["SCRIPT_FILENAME"];
        }*/
        /// Now do the directory location variables that only require the doc_root as a prerequisite
        $this->app_name = "Whiteacorn";
        $this->ctl_root         = $this->doc_root."/ctl";
        $this->data_root        = $this->doc_root."/data";
        $this->php_root         = $this->doc_root."/php";
		$this->config_path		= (is_null($this->config_path))	?	$this->php_root."/config" 
																: 	$this->config_path;
        $this->script_root      = $this->doc_root."/scripts";
        $this->temporary_root   = $this->doc_root."/temporary";
        $this->skins_root       = $this->doc_root."/skins";
        $this->logger_root      = $this->data_root."/logs/";
        //$this->content_root     = $this->data_root."/rtw/content";
        $this->articles_dir     = $this->data_root."/articles/";
        $this->notfound         = $this->doc_root."/notfound.php";
        //
        // Version number
        //
        $this->vers = $this->load_config_file("version.php");
        //
        // Database
        //
        $this->db = $this->load_config_file("database.php");
        //
        // URL routing to controllers
        //
        $this->app_route = $this->load_common_config_file("route.php");
        //
        // Autoloader - where it looks for class files
        //
        $this->autoloader_table = $this->load_common_config_file("autoloader_table.php");

    }
    function trip_root($trip){
        return $this->data_root."/$trip";
    }
    function content_root($trip){
        return $this->data_root."/$trip/content";
    }
    function url_content_root($trip){
        return $this->url_data_root."/$trip/content";
    }
    function url_skin_resource($skin, $resource){
        //print "<p>".__METHOD__."($skin, $resource)</p>";
        $res = $this->url_skins_root."/$skin$resource";
        //var_dump($res);
        //print "<p>".__METHOD__."($skin, $resource)</p>";
        return $res;
    }
    function url_skin_img($skin, $img_name){
        //print "<p>".__METHOD__."($skin, $img_name)</p>";
        $res = $this->url_root."/".$this->url_skin_resource($skin, "/images$img_name");
        //var_dump($res);
        //print "<p>".__METHOD__."($skin, $img_name)</p>";
        return $res;
    }
    /*!
    * This function loads an associative array from a file and returns it
    * the file location depends on the system being run -dev, prod, local-prod, test
    */
    function load_config_file($fn){
        //var_dump($path);
        include $this->config_path."/".$this->system_mode."/".$fn;
        return $cfg;
    }
    /*
    ** Load a config file that is common across all system modes
    */
    function load_common_config_file($fn){
        include $this->config_path."/".$fn;
        return $cfg;
    }
    /*!
    * Determines the document root and the system mode
    * Sets $this->docu_root
    * sets $this->mode = ('prod', 'dev', 'test', ) 
    */
    function doc_root_and_mode($_server)
    {   
        $server_name = $_server['SERVER_NAME'];
        $server_doc_root = $_server['DOCUMENT_ROOT'];   
        
        $server_name_to_mode = array(
            'test.whiteacorn'   =>  array('mode'=>'dev','root'=>$server_doc_root),
            'brendonblackwell'  =>  array('mode'=>'dev','root'=>$server_doc_root),
            'blackwellrn'       =>  array('mode'=>'dev','root'=>$server_doc_root),
            'nwa'               =>  array('mode'=>'dev','root'=>$server_doc_root),

            'www.test_whiteacorn.com'   =>  array('mode'=>'test','root'=>'test_whiteacorn/live'),
            'test_whiteacorn.com'       =>  array('mode'=>'test','root'=>'test_whiteacorn/live'),

            'whiteacorn'        =>  array('mode'=>'prod_local','root'=>$server_doc_root),
            
            'www.whiteacorn.com'    =>  array('mode'=>'prod','root'=>'whiteacorn/live'),
            'whiteacorn.com'        =>  array('mode'=>'prod','root'=>'whiteacorn/live'),
        );
               
        //print "<h1>".__METHOD__."setDocumentRoot</h1>";
        //$server_name = $_server['SERVER_NAME'];
        //$server_doc_root = $_server['DOCUMENT_ROOT'];   

        if ($server_name=="whiteacorn" )
        {
            //print "<p>sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root ;
            $mode = "prod_local";
        }
        else if (($server_name=="www.whiteacorn.com") ||
                ($server_name=="whiteacorn.com") )
        {
            //print "<p>sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root . '/whiteacorn';
            $mode = "prod";
        }
        else if (($server_name=="test.whiteacorn.com"))
        {
            //print "<p>sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root . '/test_whiteacorn';
            $mode = "test";
        }
        else if (($server_name=="www.iracoon.com")
                ||($server_name=="iracoon.com")) 
        {
            //print "<p>sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root . '/iracoon';
            $mode = "test";
        }
        else if (($server_name=="www.oziblackwells.com")||($server_name=="oziblackwells.com"))
        {
            //print "<p>sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root . '/oziblackwells.com';
            $mode = "test";
        }
        else if (($server_name=="test.whiteacorn")) {
            //print "<p> sn: $server_name; : dR: $doc_root;</p>";
            $doc_root = $server_doc_root;
            $mode = "dev";
        } 
        else
        {
            $doc_root = $server_doc_root;
            $mode = "dev";
/*            print "AAAAAZZZZZZZZZZZZZ big trouble server is : " . $server_doc_root ."<br>";
            print "SERVER documen root is : " . $server_doc_root ."<br>";
            print "whiteacorn doc root ".$doc_root."<br>";
            var_dump($_server); 
*/
        }
        $this->doc_root = $doc_root;
        $this->doc_root = $this->xroot;
        $this->system_mode = $mode;
    }

}
?>
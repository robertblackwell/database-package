<?php
namespace Database;

use \Database\Models\Entry as Entry;

/**
*
* @brief This class knows how to create all the sql tables and views in the mysql database.
*
* One method is provided for each tables/view and one method for all tables.
*
* Also methods are provided for upgrades that have taken place to the sql database.
* These upgrade methods will amend a live database
*/
class Builder
{
	/**
	 * @var \Database\SqlObject $sql
	 */
	public $sql;
	/**
	 * @var \Database\Locator $locator
	 */
	public $locator;
	/**
	* Constructor
	 * @return Builder
	*/
	public function __construct()
	{
		$this->sql = \Database\SqlObject::get_instance();
		$this->locator = \Database\Locator::get_instance();
	}

	/**
	 * Truncate all tables
	 * @return void
	 */
	public function truncate_tables()
	{
		$this->sql->query("TRUNCATE table IF EXISTS categorized_items");
		$this->sql->query("TRUNCATE view IF EXISTS categories");
		$this->sql->query("TRUNCATE table IF EXISTS albums");
		$this->sql->query("TRUNCATE table IF EXISTS banners");
		$this->sql->query("TRUNCATE table IF EXISTS editorials");
		$this->sql->query("TRUNCATE table IF EXISTS my_items");
	}

	/**
	* Truncate categorized_items table
	 * @return void
	*/
	public function truncate_categorized_items_table()
	{
		$this->sql->query("TRUNCATE table IF EXISTS categorized_items");
	}
	/**
	* Truncate categories view
	 * @return void
	*/
	public function truncate_categories_view()
	{
		$this->sql->query("TRUNCATE view IF EXISTS categories");
	}
	/**
	* Truncate albums table
	 * @return void
	*/
	public function truncate_albums_table()
	{
		$this->sql->query("TRUNCATE table albums");
	}
	/**
	 * Truncate my_items table
	 * @return void
	 */
	public function truncate_my_items_table()
	{
		$this->sql->query("TRUNCATE table IF EXISTS my_items");
	}

	/**
	 * Drop all tables
	 * @return void
	 */
	public function drop_tables()
	{
		$this->sql->query("DROP table IF EXISTS categorized_items");
		$this->sql->query("DROP table IF EXISTS categories");
		$this->sql->query("DROP view IF EXISTS categories");
		$this->sql->query("DROP table IF EXISTS albums");
		$this->sql->query("DROP table IF EXISTS banners");
		$this->sql->query("DROP table IF EXISTS editorials");
		$this->sql->query("DROP table IF EXISTS my_items");
	}

	/**
	 * Create all tables
	 * @return void
	 */
	public function create_tables()
	{
		$this->drop_tables();
		$this->create_table_my_items();
		$this->create_table_categorized_items();
		$this->create_view_categories();
		$this->create_table_albums();
		$this->create_table_banners();
		$this->create_table_editorials();
	}

	/**
	 * Create my_items table
	 * @return void
	 */
	public function create_table_my_items()
	{
		$query_my_items = " 
			CREATE TABLE `my_items` (
				`slug` varchar(20)   CHARSET UTF8 NOT NULL DEFAULT '',
				`version` varchar(20)	  CHARSET UTF8 DEFAULT NULL,
				`type` varchar(20)   CHARSET UTF8 DEFAULT NULL,
				`status` varchar(20) CHARSET UTF8	 DEFAULT NULL,
				`creation_date` date DEFAULT NULL,
				`published_date` date DEFAULT NULL,
				`last_modified_date` date DEFAULT NULL,
				`trip` varchar(50)   CHARSET UTF8 DEFAULT NULL,
				`vehicle` varchar(20)   CHARSET UTF8 DEFAULT NULL,
				`title` mediumtext  CHARSET UTF8,
				`abstract` mediumtext	 CHARSET UTF8,
				`excerpt` mediumtext	CHARSET UTF8,
				`camping` mediumtext	CHARSET UTF8,
				`miles` varchar(11) DEFAULT NULL,
				`odometer` varchar(11) DEFAULT NULL,
				`day_number` int(11) DEFAULT NULL,
				`latitude` double DEFAULT NULL,
				`longitude` double DEFAULT NULL,
				`country` varchar(80) CHARSET UTF8  DEFAULT NULL,
				`place` varchar(80) CHARSET UTF8	DEFAULT NULL,
				`featured_image` text CHARSET UTF8 ,
				PRIMARY KEY (`slug`),
				KEY `country` (`country`)
			) ENGINE=InnoDB DEFAULT CHARSET UTF8;";

		$result = $this->sql->query($query_my_items);
			
	}

	/**
	* Create albums table
	 * @return void
	*/
	public function create_table_albums()
	{
		$query = " 
			CREATE TABLE `albums` (
				`slug` varchar(20) CHARSET UTF8  NOT NULL DEFAULT '',
				`version` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`type` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`status` varchar(20) CHARSET UTF8	 DEFAULT NULL,
				`creation_date` date DEFAULT NULL,
				`published_date` date DEFAULT NULL,
				`last_modified_date` date DEFAULT NULL,
				`trip` varchar(50) CHARSET UTF8  DEFAULT NULL,
				`title` mediumtext CHARSET UTF8 ,
				`abstract` mediumtext CHARSET UTF8 ,
				PRIMARY KEY (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET UTF8;";
				
		$result = $this->sql->query($query);
				
	}
	/**
	 * Drops a table "banner" from the sql database
	 * @return void
	 */
	public function drop_table_banners() : void
	{
		$this->sql->query("DROP table IF EXISTS banners");
	}
	/**
	 * Drops a table "editorial" from the sql database
	 * @return void
	 */
	public function drop_table_editorials()
	{
		$this->sql->query("DROP table IF EXISTS editorials");
	}

	/**
	 * Create banners table
	 * @return void
	*/
	public function create_table_banners()
	{
		$query = " 
			CREATE TABLE `banners` (
				`version` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`type` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`slug` varchar(20) CHARSET UTF8  NOT NULL DEFAULT '',
				`status` varchar(20) CHARSET UTF8	 DEFAULT NULL,
				`creation_date` date DEFAULT NULL,
				`published_date` date DEFAULT NULL,
				`last_modified_date` date DEFAULT NULL,
				`trip` varchar(50) CHARSET UTF8  DEFAULT NULL,
				PRIMARY KEY (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET UTF8;";
				
		$result = $this->sql->query($query);
				
	}


	/**
	* Create editorial table
	 * @return void
	*);
	*/
	public function create_table_editorials()
	{
		$query = " 
			CREATE TABLE `editorials` (
				`version` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`type` varchar(20) CHARSET UTF8  DEFAULT NULL,
				`slug` varchar(20) CHARSET UTF8  NOT NULL DEFAULT '',
				`status` varchar(20) CHARSET UTF8	 DEFAULT NULL,
				`creation_date` date DEFAULT NULL,
				`published_date` date DEFAULT NULL,
				`last_modified_date` date DEFAULT NULL,
				`trip` varchar(50) CHARSET UTF8  DEFAULT NULL,
				`main_content` mediumtext CHARSET UTF8 ,
				`image_name` mediumtext CHARSET UTF8 ,
				PRIMARY KEY (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET UTF8;";
				
		$result = $this->sql->query($query);
				
	}

	/**
	* Create categories view
	 * @return void
	*/
	public function create_view_categories()
	{

		$query_categories=<<<EOD
			create view categories as (
			select distinct categorized_items.category, my_items.trip 
			from categorized_items 
			inner join my_items 
			on categorized_items.item_slug = my_items.slug)
EOD;

		$this->sql->query($query_categories);
	}
	/**
	* Create categories view
	 * @return void
	*/
	public function create_view_categories_trip()
	{

		$query_categories=<<<EOD
			create view categories_by_trip as (
			select distinct categorized_items.category, my_items.trip 
			from categorized_items 
			inner join my_items 
			on categorized_items.item_slug = my_items.slug)
EOD;

		$this->sql->query($query_categories);
	}
	
	/**
	* Create categories table
	 * @return void
	* @deprecated
	*/
	public function create_table_categories()
	{
		$query_categories=<<<EOD
		CREATE TABLE `categories` (
			`category` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
			PRIMARY KEY (`category`)
		) ENGINE=InnoDB DEFAULT CHARSET UTF8;
EOD;
		$this->sql->query($query_categories);
	}

	/**
	* Create categorized_items table
	 * @return void
	*/
	public function create_table_categorized_items()
	{
		$query_categorized_items=<<<EOD
			CREATE TABLE `categorized_items` (
			`category` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
			`item_slug` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
			PRIMARY KEY (`category`,`item_slug`),

			CONSTRAINT `categorized_items_ibfk_1` 
			FOREIGN KEY (`item_slug`) REFERENCES `my_items` (`slug`)
				on update cascade
				on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET UTF8;
EOD;
		$this->sql->query($query_categorized_items);
	}

	/**
	* Modify my_items table to add camping field
	 * @return void
	*/
	public function add_camping()
	{
		$query_my_items = "ALTER TABLE `my_items` ADD `camping` MEDIUMTEXT after `featured_image`";
	}

	/**
	* Create all fix country in an item
	 * @param \Database\Models\Entry $e Entry to be fixed.
	 * @return void
	*/
	public static function fix_country(Entry $e)
	{
		if (get_class($e) != '\Database\Models\Entry') {
			return;
		}
		$t = ["North West Territory"=>"NWT", "British Columbia"=>"BC","Alberta"=>"Alberta", "Yukon"=>"Yukon"];
	
		$c = Country::look_up($e->country);
		if ($c == "USA") {
			$country = "USA, ".$e->country;
			$e->country = $country;
		} elseif ($c == "Canada") {
			$country =  "Can, ".$t[$e->country];
			$e->country = $country;
		}
	}
}

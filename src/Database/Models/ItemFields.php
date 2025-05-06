<?php
namespace Database\Models;
/**
 * The rows of my_items table are variant records with the  'type' field acting as the variant tag. The alternative
 * interpretations of a row are:  
 * -    a summary of an Entry - without the main_content, location, and distance data
 * -    a summary of a Post - without main_content
 * -    a summary of an Article - without main content
 * 
 * An Item instance is required to represent a summary of a Post, Entry or Article.
 * In order to do this when an Item instance is loaded from the my_items table information is required
 * as to what row fields should be loaded and which of those are permitted to have a null value.
 * 
 * This class aims to systematize and capture that information in one place.
 * 
 * In addition when an Entry, Post or Article instance is loaded from a HEDobject (and thats the only what they are ever
 * filled in) we still need information about which fields must be present in the HEDobject in order to create a valid
 * Entry, Post or Article.
 *  
 */
class ItemFields
{
    // these are the fields in the my_items table (with their type) that all variants require to be not null
	private static $core_field_myitems = [
		"version"=>"text",
		"slug"=>"text",
		"type"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",
	];
    // the extra fields required to be none null for a valid Entry variant of a my_items row to be loaded
	private static $entry_extra_field_myitems = [ 
		"excerpt"=>"text",
		"miles"=>"text",
		"odometer"=>"int",
		"day_number"=>"int",
		"latitude"=>"latitude",
		"longitude"=>"longitude",
		"country"=>"text",
		"place"=>"text",
		// "featured_image"=>'text',
	];
    // the extra fields required to be none null for a valid Post variant of a my_items row to be loaded
	private static $post_extra_field_myitems = [
		"excerpt" => "text",
		// "featured_image" => "text",
	]; 
    // the extra fields required to be none null for a valid Article variant of a my_items row to be loaded
	private static $article_extra_field_myitems = [
		"abstract" => "text",
	];
	public static $field_names = [
		"version"=>"text",
		"slug"=>"text",
		"type"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",

		"excerpt"=>"text",
		"miles"=>"text",
		"odometer"=>"int",
		"day_number"=>"int",
		"latitude"=>"latitude",
		"longitude"=>"longitude",
		"country"=>"text",
		"place"=>"text",
		"featured_image"=>'text',
		"camping"=>"html",

		"excerpt" => "text",
		"featured_image" => "text",

		"abstract" => "text",
	];
    public $core_myitems_fields;

    public $entry_required_myitems_fields;
    public $entry_optional_myitems_fields;
    public $entry_all_myitems_fields;
    public $entry_required_entryrecord_fields;
    public $entry_optional_entryrecord_fields;

    public $post_required_myitems_fields;
    public $post_optional_myitems_fields;
    public $post_all_myitems_fields;
    public $post_required_postrecord_fields;
    public $post_optional_postrecord_fields;

    public $article_required_myitems_fields;
    public $article_optional_myitems_fields;
    public $article_all_myitems_fields;
    public $article_required_articlerecord_fields;
    public $article_optional_articlerecord_fields;

    public $sql_myitems_fields;

    private static $instance = null;
    public static function getInstance(): ItemFields
    {
        if(self::$instance === null) {
            self::$instance = new ItemFields();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->entry_required_myitems_fields = array_merge(self::$core_field_myitems, self::$entry_extra_field_myitems);
        $this->entry_optional_myitems_fields = ["camping"=>"text", "featured_image" => "text"];
        $this->entry_all_myitems_fields = array_merge($this->entry_required_myitems_fields, $this->entry_optional_myitems_fields);
        $this->entry_required_entryrecord_fields = array_merge($this->entry_required_myitems_fields, ["main_content"=>"html"]);
        $this->entry_optional_entryrecord_fields = array_merge($this->entry_optional_myitems_fields, ["border"=>"html", "vehicle"=>"text"]);

        $this->post_required_myitems_fields = array_merge(self::$core_field_myitems, self::$post_extra_field_myitems);
        $this->post_optional_myitems_fields = ["featured_image"=>"text"];
        $this->post_all_myitems_fields = array_merge($this->post_required_myitems_fields, $this->post_optional_myitems_fields);
        $this->post_required_postrecord_fields = array_merge($this->post_required_myitems_fields, ["main_content"=>"html"]);
        $this->post_optional_postrecord_fields = array_merge($this->post_optional_myitems_fields, ["topic"=>"text"]);

        $this->article_required_myitems_fields = array_merge(self::$core_field_myitems, self::$article_extra_field_myitems);
        $this->article_optional_myitems_fields = [];
        $this->article_all_myitems_fields = array_merge($this->article_required_myitems_fields, $this->post_optional_myitems_fields);
        $this->article_required_articlerecord_fields = array_merge($this->article_required_myitems_fields, ["main_content"=>"html"]);
        $this->article_optional_articlerecord_fields = array_merge($this->article_optional_myitems_fields, []);

        $this->sql_myitems_fields = array_keys(array_merge($this->entry_required_myitems_fields, $this->entry_optional_myitems_fields,
                                                $this->post_required_myitems_fields, $this->post_optional_myitems_fields,
                                                $this->article_required_myitems_fields, $this->article_optional_myitems_fields));

    }

}
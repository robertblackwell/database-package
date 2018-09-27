<?php
namespace Database\HED;

use \DOMDocument as DOMDocument;

/**
 * @brief This class extends the DOM class DOMDocument to add a couple of extra methods that I like to have available
 *
*/
class ExtendedDOMDocument
{
	private $document;
	/**
	* create
	* Creates a new ExtendedDOMNode from a standard DOMNode by wrapping the original $node.
	* @param DOMNode $node The DOMNode to be wrapped.
	* @return ExtendedDOMNode
	*/
	public static function create(DOMNode $node) : ExtendedDOMDocument
	{
		print "ExtendedDOMDocument::create \n";
		// var_dump($doc);
		$obj = new ExtendedDOMDocument();
		$obj->document = $node;
		return $obj;
	}
	/**
	* Creates a new node with the give tag and creates under it the children necessary to implement the html text
	* apssed in.
	* @param string $tag  HTML tag to wrap the text.
	* @param string $html Html Text.
	* @return DOMNode
	*/
	public function createElementFromHTML(string $tag, string $html) : DOMNode
	{
		//print "ExtendedDOMDocument::createElementFromHTML ($tag, $html) \n";
		//var_dump($this->document);
		$d = new DOMDocument();
		$d->loadHTML($html);
		$body = $d->getElementsByTagName("body")->item(0);
		$el = $this->document->createElement($tag);
		foreach ($body->childNodes as $child) {
			$child = $this->document->importNode($child, true);
			$child = $el->appendChild($child);
		}
		return $el;
	}
}

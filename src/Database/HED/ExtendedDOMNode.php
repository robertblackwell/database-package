<?php
namespace Database\HED;

use \DOMDocument as DOMDocument;

/**
 * This class extends the DOM class DOMNode to add a couple of extra methods that I like to have available
 *
*/
class ExtendedDOMNode
{
	private $_node;
	/**
	* create
	* Creates a new ExtendedDOMNode from a standard DOMNode
	* @param DOMNode $node The DOMNode to be wrapped.
	* @return ExtendedDOMNode
	*/
	// public static function create(DOMNode $node) : ExtendedDOMNode
	public static function create(\DOMElement $node) : ExtendedDOMNode
	{
		$obj = new ExtendedDOMNode();
		$obj->_node = $node;
		return $obj;
	}
	/**
	* hex
	* Returns the inner html text of the ExtendedDOMNode as a hexidecimal string
	* @return string
	*/
	public function hex() : string
	{
		$s = $this->innerHTML();
		//print "hex: length : " . strlen($s);
		$out = "";
		for ($i = 0; $i < strlen($s); $i++) {
			$ch = $s[$i];
			$out = $out . sprintf("%d ", $ch);
		}
		return $out;
	}
	/**
	* fullHTML
	* Returns the full html text of the ExtendedDOMNode
	* @return string
	*/
	public function fullHTML()
	{
		$doc = new DOMDocument();
		$doc->appendChild($doc->importNode($this->_node, true));
		return $doc->saveHTML();
	}
	/**
	* fullHTML
	* Returns the inner html text of the ExtendedDOMNode. That is the html for all child nodes
	* @return string
	*/
	public function innerHTML() : string
	{
		$doc = new DOMDocument();
		foreach ($this->_node->childNodes as $child) {
			$doc->appendChild($doc->importNode($child, true));
		}
		return $doc->saveHTML();
	}
	/**
	* Appends a new child node (and its children) that is created from the tag and html
	* @param string $tag  An html tag name.
	* @param string $html Html text to be wrapped in the $tag.
	* @return  DOMNode  the node appended
	*/
	public function appendChildFromHTML(string $tag, string $html) : DOMNode
	{
		//print "ExtendedDOMNode::appendChildFromHTML  ($tag  $html)\n";
		$d = $this->_node->ownerDocument;
		// var_dump($this->_node->ownerDocument);
		$xd = ExtendedDOMDocument::create($d);
		$n = $xd->createElementFromHTML($tag, $html);
		$n = $this->_node->appendChild($n);
		return $n;
	}
	/**
	* Sets the inner HTML for this node to the given string
	* apssed in.
	* @param string $html The new value for inner html.
	* @return void
	*/
	public function setInnerHTML(string $html)
	{
		if ($debug) print "ExtendedDOMNode::setInnerHTML  this= ". $this->innerHTML() ."  html: $html\n";
		$newEl = ExtendedDOMDocument::create($this->_node->ownerDocument)->createElementFromHTML("div", $html);
		$n = $this->_node;
		while ($n->hasChildNodes()) {
			if (!($tmp = $n->removeChild($n->firstChild))) die("ExtendedDOMNode::setInnerHTML - remove failed");
		}
		foreach ($newEl->childNodes as $nChild) {
			if (!($cn = $this->_node->appendChild($this->_node->ownerDocument->importNode($nChild->cloneNode(true)))))
				die("ExtendedDOMNode::setInnerHTML - append failed");
		}
	}
	/**
	* Sets the outer HTML for this node to the given string
	* apssed in.
	* @param string $html The new value fo the instances outter html.
	* @return void
	*/
	public function setOuterHTML(string $html) : void
	{
		if ($debug) print "ExtendedDOMNode::setInnerHTML  this= ". $this->innerHTML() ."  html: $html\n";
		$newEl = ExtendedDOMDocument::create($this->_node->ownerDocument)->createElementFromHTML("div", $html);
		$n = $this->_node;
		$p = $n->parentNode;
		$d = $n->ownerDocument;
		foreach ($newEl->childNodes as $nChild) {
			$cc = $nChild->cloneNode(true);
			$cc = $d->importNode($cc);
			$cn = $p->insertBefore($cc, $n);
			if (!($cn))
				die("ExtendedDOMNode::setOuterHTML - insertBefore failed");
		}
		if (!$p->removeChild($n))
			die("ExtendedDOMNode::setOuterHTML - remove failed");
	}
	/**
	* Magic __set method only works for innerHTML and outterHTML
	* @param string $name  The property name to be set.
	* @param string $value The new value of the property.
	* @return void
	* @throws \Exception If $name is not inner html or outter html.
	*/
	public function __set(string $name, string $value) : void
	{
		if ($debug) print "XXXXXXXXXXXX__set called  name : $name value : $value\n";
		if ($name == "outerHTML") {
			$this->setOuterHTML($value);
			return;
		} else if ($name == "innerHTML") {
			$this->setInnerHTML($value);
			return;
		}
		die("ExtendedDOMNode set property $name unsupported");
	}
	/**
	* Magic __get method only works for innerHTML and outterHTML
	* @param string $name The property name to be "get"'d.
	* @return string
	* @throws \Exception If $name is not inner html or outter html.
	*/
	public function __get(string $name)
	{
		if ($debug) print "XXXXXXXXXXX__get called  name : $name \n";
		if ($name == "outerHTML")
			return $this->outerHTML();
		else if ($name == "innerHTML")
			return $this->innerHTML();
		die("ExtendedDOMNode get property $name unsupported");
	}
}

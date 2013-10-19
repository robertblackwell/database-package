<?php
/*!
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 */
/*! @ingroup Gallery
 * Provides functions which emit the HTML necessary to make one or more gallery summary/ies
 * visible on a page.
 *
 * <pre>
 *	Gallery_Definitions::Begin          - 	called to start a list of photo galleries
 *		.....
 *	Gallery_Definitions::OneGallery     -	called once for each item in the list of galleries
 *		.....
 *	Gallery_Definitions::End            -	called to end a list og galleries
 * </pre>
 * Gallery_Object provides an object that represents a photo gallery, including all the images and thumbnails
 * 
 * @category  Whiteacorn
 * @package   Gallery
*/
class Gallery_Definitions
{
    static function Header()
    {

        $root = Registry::$globals->doc_root;

        $hdstr =<<< EOS
        <link href="{$root}/css/gallery.css" rel="stylesheet" type="text/css"/>
EOS;
    //echo $hdstr;
    }
    /*!
    * Generate the HTML for a trip gallery.
     * @param string $trip
     * the trips directory name 
     * @param string $dir
     * the directory within the trip
     * @param string $gal
     * @param string $trip - site relative directory name
    */
    static function TripGallery($trip, $dir, $gal){
    	self::OneGallery("/".$trip."/".$dir, $gal, $trip);
    }
    /*!
     *
     * Defines a gallery to be made available for a trip. These types of galleries initially show
     * one mascot photo as a link to the rest of the gallery.
     *
     * The "trip" or home directory must be provided so that the script that builds the page knows
     * what templates to us.
     *
     * @global BOOL $debug
     * @param string $dir
     * @param string $gal
     * @param string $trip - site relative directory name
     */
    static function OneGallery ($dir, $gal, $trip="theamericas")
    {
        $documentRoot =  Registry::$globals->doc_root;
        global $debug;
        $trStart="";//<tr>";
        $trEnd="";//</tr>";
		$mygal = Gallery_Object::create($dir.'/'.$gal);

        $firstImg = $mygal->images[0];

        if ($debug) print "<p>firstImage : ".$firstImg->getName()."</p>";
       
        $galDir = $mygal->getName();
        $galDescriptionPath = $galDir . 'description.html';

        if ($debug) print "<p>galleryList galDir: $galDir</p>";
        $galLink = "/scripts/Gallery.php?action=showgallery&amp;gal=$galDir&amp;trip=$trip";

        $myHeight = $firstImg->getThumbnailHeight() . 'px';
        $h2 = $mygal->getDescriptionHeading();
        $image = str_replace(" ","",$h2);
     
        $mascotImagePath = '/images/photosIndex/'.strtolower($image).'.jpg';

     	$mascotImagePath = $dir.'/'.$gal.'/mascot.jpg';
     	$alt=$gal;
        $s = 
        		"\t<a href=\"".$galLink."\">\n" .
		        "\t<div class='galleryItem'>\n" .
		        "\t<img src='$mascotImagePath' alt='$alt'/>\n" .
		        "\t<h2 class='galleryHeading'>$h2</h2>\n" .
		        "\t<br class='clear'/></div>\n" .
		        "\t</a>\n";
        $s2 = 
        		"\t<a href=\"".$galLink."\">\n" .
		        "\t\t<span class='galleryItem'>\n" .
		        "\t\t\t<img src='$mascotImagePath' alt='$alt'/>\n" .
		        "\t\t\t<span class='galleryHeading'>$h2</span>\n" .
		        "\t\t\t<br class='clear'/>\n" . 
		        "\t\t</span>\n" .
		        "\t</a>\n";
		print $s2;
        $line1 = ""; $line2="";
    }
    /*!
     * Begin - starts a series of gallery definitions.
     */
    static function Begin()
    {
        print "<div class='GalleryList'><!--begin gallery -->";
    }
    /*!
     * End  ends a series of Gallery definitions
     */
    static function End()
    {
        print "</div><!--end gallery -->";;
    }

    /*
     * Used to display the Image and Thumbnail
     * subdirectories of $gal_name as a photo gallery.
     * @params $gal_name string  the site relative path of the dir containing the Image and Thumbnails subdirs
     * @globals gets the location of the images by deduction from the $_SERVER value
     */
	static function OneInlineGallery($gal_name_in, $add_break=true)
	{
//		print "<p>".__METHOD__." $add_break: ".(($add_break)?"true":"false")."</p>";
		$documentRoot =  Registry::$globals->doc_root;	
		//global $debug;
		$debug = false;
		$printCaptions = false;
		if ($debug) print "Just to show we got here";
		if ($debug) print "Gallery_Definitions::OneGallery entered<br>";
		if ($debug) print "Inut name is : ". $gal_name_in ."<br>";
		if ($debug) print " is it null " . (int)($gal_name_in == NULL). "<br>";
		if ($debug) var_dump($_SERVER);
		/*$a = preg_split("[/]", $_SERVER['PHP_SELF']);
		var_dump($a);
		$jEntry = $a[3];*/
		if ($debug) print "Gallery_Definitions::OneJournalgallery the journal entry is -$jEntry<br>";
		$ent_path = dirname($_SERVER['PHP_SELF']);
		if ($debug) print "<br>path name $ent_path<br>";
		/*$galName = "/journals/entries/" . $jEntry;*/
		$galName = $gal_name_in;
		if ($debug) print "galName : $galName<br>";

        $mygal = Gallery_Object::create($galName);
        if ($debug) var_dump($mygal);
       //$h = $mygal->getDescriptionHeading();
       $h="";
		if ($debug) print "heading is : $h";
		if ($mygal->getImageCount() > 0){
        	print "\t<h2 id='ThumbnailHeading'>$h</h2>\n";
        	if($add_break) print "<br/>";
			print "\t\t<div id='ThumbnailsDiv'>\n";
	        for ($j = 0; $j < $mygal->getImageCount(); $j++) {
	            $i = $j;
	            $img = $mygal->images[$i];
	            $imageURL = $img->getSiteRelativeImageURL();
	            $thumbnail = $img->getSiteRelativeThumbnailURL();
				$title = $img->getName();
				/*
				* $title is where we would put the captions if we had one
				*/
				$title = $mygal->captions->getCaption($img);
				if (($title == "") || ($title == NULL))
					$title = $img->getName();
				$aa= preg_split("[/]",$mygal->getName());
				$group = $aa[count($aa)-1];
	            print 	"\t\t\t<a href=\"$imageURL\" rel=\"lightbox[$group]\" title=\"$title\">\n".
	            		"\t\t\t\t<img src=\"$thumbnail\"/>\n".
	            		"\t\t\t</a>\n";
	        }
	        print "\t\t</div><!-- close thumbnail div -->\n";
        }	
    }


    /*
     * Used in a journal entry page to display the in the Image and Thumbnail
     * subdirectories as a photo gallery.
     * Deduces the journa entries directory name from $_SERVER['PHP_SELF']
     * @params none
     * @globals gets the location of the images by deduction from the $_SERVER value
     */
	static function OneJournalGallery($gal_name_in=null)
	{
		$documentRoot =  Registry::$globals->doc_root;	
		//global $debug;
		$debug = false;
		$printCaptions = false;
		if ($debug) print "Just to show we got here";
		if ($debug) print "Gallery_Definitions::OneJournalgallery entered<br>";
		if ($debug) print "Inut name is : ". $gal_name_in ."<br>";
		if ($debug) print " is it null " . (int)($gal_name_in == NULL). "<br>";
		if ($debug) var_dump($_SERVER);
		$a = preg_split("[/]", $_SERVER['PHP_SELF']);
		//var_dump($a);
		$jEntry = $a[3];
		if ($debug) print "Gallery_Definitions::OneJournalgallery the journal entry is -$jEntry<br>";
		$ent_path = dirname($_SERVER['PHP_SELF']);
		if ($debug) print "<br>path name $ent_path<br>";
		$galName = "/journals/entries/" . $jEntry;
		$galName = $ent_path;
		if ($gal_name_in != null){
			$galName = $gal_name_in;
			//print "Changed gal name<br>";
		}
		if ($debug) print "galName : $galName<br>";
		//$mygal = new Gallery();
		//$mygal->loadGallery($documentRoot, $galName);
        $mygal = Gallery_Object::create($galName);
        if ($debug) var_dump($mygal);
       //$h = $mygal->getDescriptionHeading();
       $h="";
		if ($debug) print "heading is : $h";
		if ($mygal->getImageCount() > 0){
        	print "\t<h2 id='ThumbnailHeading'>$h</h2>\n<br/>";
			print "\t\t<div id='ThumbnailsDiv'>\n";
	        for ($j = 0; $j < $mygal->getImageCount(); $j++) {
	            $i = $j;
	            $img = $mygal->images[$i];
	            $imageURL = $img->getSiteRelativeImageURL();
	            $thumbnail = $img->getSiteRelativeThumbnailURL();
				$title = $img->getName();
				/*
				* $title is where we would put the captions if we had one
				*/
				$title = $mygal->captions->getCaption($img);
				if (($title == "") || ($title == NULL))
					$title = $img->getName();
				$aa= preg_split("[/]",$mygal->getName());
				$group = $aa[count($aa)-1];
	            print 	"\t\t\t<a href=\"$imageURL\" rel=\"lightbox[$group]\" title=\"$title\">\n".
	            		"\t\t\t\t<img src=\"$thumbnail\"/>\n".
	            		"\t\t\t</a>\n";
	        }
	        print "\t\t</div><!-- close thumbnail div -->\n";
        }	
    }
    /*!
    * Emits the html to shows all the images from a
    * journal entry gallery in "contact print" format.
    * It works out the identity of the journal entry from the name
    * of the calling page. This is the second version that DOES
    * use lightbox.
    * @return void
    */
	static function V1_OneJournalGallery()
	{
		$documentRoot =  Registry::$globals->doc_root;	
		global $debug;

		$printCaptions = false;

		if ($debug) print "Gallery_Definitions::OneJournalgallery entered<br>";
		$a = preg_split("[/]", $_SERVER['PHP_SELF']);
		$jEntry = $a[3];
		if ($debug) print "Gallery_Definitions::OneJournalgallery the journal entry is -$jEntry=";
		$galName = "/journals/entries/" . $jEntry;
		//$mygal = new Gallery();
		//$mygal->loadGallery($documentRoot, $galName);
        $mygal = Gallery_Object::create($galName);
		print "<div>" . "\n\t" . "<table class='GalleryTable'>" . "\n\t\t";
		//print "<h2>$h</h2>\n<br/>";
		$line1 = ""; 	$line2 = "";
		$startTdImg =     "\t\t\t" . 	"<td class='GalleryImg'>"; 
		$startTdCaption = "\t\t\t" . "<td class='GalleryImgxCaption' >" . "\n";
		$n = 0;
		for ($j = 0; $j < $mygal->getImageCount(); $j++)
		{
			$i = $j;
			$img = $mygal->images[$i];
			$hrefstring = $img->getShowImageLink();
			$thumbnail = $img->getThumbnailURL();

			$s1 = $startTdImg . "<a href=$hrefstring target='_blank'><img src=$thumbnail/></a>".
							"</td>" . 
							"\n";		
			$s2= $startTdCaption . "abc $img->getName()</td>" . "\n";
			$line1 = $line1 . $s1;
			$line2 = $line2 . $s2;		
			$n++;
			$n = $n % 7;
			if ($n == 0) 
			{
				print "\n\t\t" . "<tr>" . "\n" . $line1 . "\t\t" . "</tr>"; 
				if ($printCaptions)
				{
					print "\n\t\t" . "<tr>" . "\n" . $line2 . "\t\t" . "</tr>"; 
				}
				$line1 = ""; $line2="";
			}
		}

		for($j = 0; ($j < 7) && ($n !=0); $j++)
		{
			print "<!--Got inside the finish loop  $line1  n: $n  j: $j -->";
			$line1 = $line1 . $startTdImg . "</td>\n";
			$line2 = $line2 . $startTdCaption ."_"."</td>\n";
			$n++;
			$n = $n % 7;
			if ($n ==0)
			{
					print "\n\t\t" . "<tr>" . "\n" . $line1 . "\t\t" . "</tr>"; 
					if ($printCaptions)
					{
						print "\n\t\t" . "<tr>" . "\n" . $line2 . "\t\t" . "</tr>"; 
					}
					$line1 = ""; $line2="";
			}
		}
		print "</table>";
		print "</div>";
	}

}
?>
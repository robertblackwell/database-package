<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
createversion = text 
	<div id="version">2.0</div>
createtype = text 
	<div id="type">post</div>
createslug = text 
	<div id="slug">slug</div>
createstatus = text 
	<div id="status">draft</div>
createcreation_date = date 
	<div id="creation_date">apost</div>
createpublished_date = date 
	<div id="published_date">apost</div>
createlast_modified_date = date 
	<div id="last_modified_date">apost</div>
createtrip = text 
	<div id="trip">trip</div>
createtitle = html 
	<div id="title">ABCDEFG</div>
createabstract = html 
	<div id="abstract">ABCDEFG</div>
createexcerpt = text 
	<div id="excerpt">ABCDEFG</div>
createtopic = text 
	<div id="topic">ABCDEFG</div>
createtags = list 
	<div id="tags">ABCDEFG</div>
createcategories = list 
	<div id="categories">ABCDEFG</div>
createfeatured_image = text 
	<div id="featured_image">ABCDEFG</div>
createmain_content = html 
	<div id="main_content">
		<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails($trip, $entry); ?>                                                                
        <p>and here</p>
	</div>

</body>
</html>
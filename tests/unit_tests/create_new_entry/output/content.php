<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
createversion = text 
	<div id="version">2.0</div>
createtype = text 
	<div id="type">entry</div>
createtrip = text 
	<div id="trip">rtw</div>
createslug = text 
	<div id="slug">170707</div>
createstatus = text 
	<div id="status">draft</div>
createcreation_date = date 
	<div id="creation_date">2017-07-07</div>
createpublished_date = date 
	<div id="published_date">2017-07-07</div>
createlast_modified_date = date 
	<div id="last_modified_date">2017-07-07</div>
createmiles = text 
	<div id="miles">ABCDEFG</div>
createodometer = text 
	<div id="odometer">ABCDEFG</div>
createday_number = text 
	<div id="day_number">ABCDEFG</div>
createplace = text 
	<div id="place">ABCDEFG</div>
createcountry = text 
	<div id="country">ABCDEFG</div>
createlatitude = text 
	<div id="latitude">ABCDEFG</div>
createlongitude = text 
	<div id="longitude">ABCDEFG</div>
createfeatured_image = text 
	<div id="featured_image">ABCDEFG</div>
createtitle = html 
	<div id="title">ABCDEFG</div>
createabstract = html 
	<div id="abstract">ABCDEFG</div>
createexcerpt = text 
	<div id="excerpt">ABCDEFG</div>
createmain_content = html 
	<div id="main_content">
		<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails($trip, $entry); ?>                                                                
        <p>and here</p>
	</div>
createcamping = html 
createborder = html 
createhas_camping = has 
createhas_border = has 

</body>
</html>
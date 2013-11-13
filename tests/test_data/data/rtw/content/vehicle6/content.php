<!DOCTYPE html>
<html>
<head></head>
<body>
<div id="PageType">page_type</div>
	<div id="version">2.0</div>
	<div id="type">post</div>
    <div id="slug">vehicle6</div>
	<div id="status">draft</div>
	<div id="creation_date">2012-05-19</div>
	<div id="published_date">2012-05-19</div>
	<div id="featured_image">/camper-choices/Thumbnails/pict-2.jpg</div>
	<div id="last_modified_date">2012-05-19</div>
	<div id="categories">vehicle,Finshed</div>
	<div id="trip">rtw</div>
    <div id="title">It is finished</div>
<div id="main_content">
    <p>We took delivery of the completed vehicle the week beginning May 7th, 2012. I spent most of that week
    at the GXV premises learning about the vehicle, dreaming up new things for them to do and completing the usual
    paper work.</p>
    <p>I have included some images of inside and outside of the vehicle</p>
    <h2>Interior</h2>
    <?php RenderArticleGallery($trip, $slug, "interior");?><h2>Exterior</h2>
    <?php RenderArticleGallery($trip, $slug, "exterior");?><h2>Top</h2>
    <?php RenderArticleGallery($trip, $slug, "top-side");?><h2>AirConditioner Tune-up</h2>
    <?php RenderArticleGallery($trip, $slug, "ac-fix");?>
</div>
</body>
</html>

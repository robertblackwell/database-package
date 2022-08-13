<?
    $title = "Our Mog";
    $article_class = "our_mog";
?>
<? include "article.php"  ; ?>
<? startblock('article_content');?>
<div id="main_content">
    <p>This article consists mainly of photographs. The majority of the images are of our yellow Unimog U500. However
    there are also images of other Unimogs. In part this is to give the reader an impression of what ours will 
    look like when it is complete. Once we have some construction photos of our vehicle they will be posted
    here also. </p>
    <?php RenderPostGallery($trip, $slug, "picts");?></div>
</body></html>
<? endblock('article_content');?>

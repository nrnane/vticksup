<div id="imageLoaded04" class="drop-zone">
        <div class="col-md-12 text-drop-zone">
            Drop your files here
        </div>
    </div>


<?php

if(isset($photos) && !empty($photos)){ 
    foreach($photos as $photo){
    ?>
<a href="<?=base_url('uploads/'.$photo['img_name'])?>" data-toggle="lightbox"><img src="<?=base_url('uploads/'.$photo['img_name'])?>" /></a>
<?php } }else{
    echo '<h1>No Photos Avaialble</h1>';
    
}
?>
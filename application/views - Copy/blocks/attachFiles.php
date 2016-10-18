<div class="clearfix"></div>
<form id="photosupload" action="<?=site_url('ajax/uploadFiles') ?>" method="POST" enctype="multipart/form-data"> 
Attach Files: <input type="file" name="images[]" id="images" multiple="true" />
</form>
<div class="clearfix"></div>

<div class="uploading" style="display: none;">
            <label>&nbsp;</label>
            <img src="<?=base_url()?>assets2/img/uploading.gif"/>
        </div>
    <div class="gallery" id="images_preview"></div>
    <div class="clearfix"></div>
    
    
    
<script type="text/javascript">
$(document).ready(function(){
	$('#images').on('change',function(){
            //alert(1);
		$('#photosupload').ajaxForm({
			target:'#images_preview',
			beforeSubmit:function(e){
				$('.uploading').show();
			},
			success:function(e){
                            console.log(e);
				$('.uploading').hide();
			},
			error:function(e){
                            console.log(e);
			}
		}).submit();
	});
});
</script>
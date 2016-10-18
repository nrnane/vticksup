</div>

 
  <!-- Libs -->
  <!--<script src='<?=base_url()?>assets2/js/jquery.min.2.1.3.js'></script>-->
  
  <script src="<?=base_url()?>assets2/js/bootstrap.min.js" ></script>
    <!--<script src="<?=base_url()?>assets2/plugins/ckeditor/ckeditor.js" ></script>
     <script src="<?=base_url()?>assets2/plugins/ckeditor/config.js" ></script>-->
     
     <script src="<?=base_url()?>assets2/plugins/tinymce/tinymce.min.js"></script>
     <script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools"
        ],
        toolbar1: "styleselect | bold italic | insertfile undo redo | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        menubar:false,
        height:200,
        content_css : "<?=base_url()?>assets2/plugins/tinymce/css/custom_content.css"
    });
    </script>

      <script src="<?=base_url()?>assets2/js/jquery.form.js" ></script>
 
    

        <link rel="stylesheet" href="<?=base_url('assets2/plugins/ekko-lightbox/ekko-lightbox.min.css')?>" charset="utf-8">
        <script src="<?=base_url('assets2/plugins/ekko-lightbox/ekko-lightbox.min.js')?>" charset="utf-8"></script>
        
        <link rel="stylesheet" href="<?=base_url('assets2/plugins/bdatepicket/datepicker.css')?>" charset="utf-8">
        <script src="<?=base_url('assets2/plugins/bdatepicket/bootstrap-datepicker.js')?>" charset="utf-8"></script>
        <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('.input-daterange').datepicker({
                    todayBtn: "linked",
                    format:"yyyy-mm-dd"
                });
            
            });
        </script>
  <script type="text/javascript">

    $('ul.nav li.dropdown').hover(function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
      }, function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
      });

  </script>
  
   <script type="text/javascript">
          $(document).ready(function ($) {
              // delegate calls to data-toggle="lightbox"
              $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
                  event.preventDefault();
                  return $(this).ekkoLightbox({
                      onShown: function() {
                          if (window.console) {
                              return console.log('Checking our the events huh?');
                          }
                      },
                      onNavigate: function(direction, itemIndex) {
                            if (window.console) {
                                return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                            }
                      }
                  });
              });

            });
  </script>
                  
 
  

    <script type="text/javascript" src="<?=base_url('assets2/js/ajax.js')?>"></script>
<div class="footer">
  <div class="container">
      &copy; 2015 VAAZU. ALL RIGHTS RESERVED
  </div>
</div>

  </body>

</html>
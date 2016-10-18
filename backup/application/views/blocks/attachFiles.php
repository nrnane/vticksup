<div class="clearfix"></div>


<link rel="stylesheet" href="<?=base_url()?>getfile/libs/cropper/cropper.css">
<link rel="stylesheet" href="<?=base_url()?>getfile/libs/filedrop/filedrop.css">
<link rel="stylesheet" href="<?=base_url()?>getfile/libs/jquery.tags/jquery.tagsinput.css">
<link rel="stylesheet" href="<?=base_url()?>getfile/css/getfile.css">

<script type="text/javascript" src="<?=base_url()?>getfile/libs/jquery/jquery-1.11.1.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>getfile/libs/cropper/cropper.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>getfile/libs/cssloader/js/jquery.cssloader.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>getfile/libs/mobiledetect/mdetect.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>getfile/libs/filedrop/filedrop.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>getfile/libs/jquery.tags/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>getfile/js/jquery.getfile.js"></script>

<!-- Script getFile -->
       <script type="text/javascript">
           $(document).ready(function()
           {
               // Rutine to delete files
               $('#tags').tagsInput({
                   'width':        '100%',
                   'height':       'auto',
                   'interactive':  false,
                   'defaultText':  '',
                   'onRemoveTag': function(value)
                   {
                       var button      = $.data(document, value);
                       var reference   = $(button).data('getFile');
                       deleteFile(reference, reference.options.folder + '/' + value);
                       $('#hidden_data input').each(function(){
                          if(this.value==value){
                            this.remove();
                          }
                       });
                   }
               });

               $('#imageLoaded04').getFile(
                   {
                       urlPlugin:          '/supportapp',
                       folder:             '/supportapp/uploads',
                       tmpFolder:          '/supportapp/tmp',
                       //encryption:         true,
                       multiple:           true
                   },
                   function(data)
                   {
                       if(data.success && data.action == "loading")
                       {
                           $('#progressBar').html(data.percentage + '%');
                           $('#progressBar').attr('aria-valuenow', data.percentage);
                           $('#progressBar').css('width', data.percentage + '%');
                       }
                       else
                       {


                           if(data.success)
                           {
                               $.each(data.files, function(index, value) {
                                   if(value.success)
                                   {

                                      $('#hidden_data').append('<input type="hidden" name="attach[]" form="create_edit_form" value="'+value.name+'" />');
                                       $('#tags').addTag(value.name);
                                       $.data(document, value.name, '#imageLoaded04');

                                       if (value.copies != undefined) {
                                           $.each(value.copies, function (index, value2) {
                                               if (value2.success) {
                                                   $('#tags').addTag(value2.name);
                                                   $.data(document, value2.name, '#imageLoaded04');
                                               }
                                           });
                                       }
                                   }
                               });
                           }
                       }
                   }
               );
           });

           function deleteFile(reference, url)
           {
               reference.delete(url, function(data)
               {
                   $('#title01').html('Delete File');
                   $('#response01').html(syntaxHighlight(JSON.stringify(data, null, "\t")));
               });
           }

           function syntaxHighlight(json)
           {
               if (typeof json != 'string') {
                   json = JSON.stringify(json, undefined, 2);
               }
               json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
               return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                   var cls = 'number';
                   if (/^"/.test(match)) {
                       if (/:$/.test(match)) {
                           cls = 'key';
                       } else {
                           cls = 'string';
                       }
                   } else if (/true|false/.test(match)) {
                       cls = 'boolean';
                   } else if (/null/.test(match)) {
                       cls = 'null';
                   }
                   return '<span class="' + cls + '">' + match + '</span>';
               });
           }
       </script>

       <div id="imageLoaded04" class="drop-zone">
             <div class="col-md-12 text-drop-zone">
                 Drop attachments here (or) Click here to select
             </div>
         </div>


         <div class="row">
                 <div class="col-md-12">
                     <div class="progress">
                         <div id="progressBar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                 </div>
             </div>


             <div class="row" >
                 <div class="" id="hidden_data">

                 </div>
                  <div class="col-md-12 " style="margin-bottom:20px;">
                      <label class="col-md-2 control-label">Attachments files:</label>
                      <div class="col-md-10"><input type="text" id="tags" class="tags" value=""></div>
                  </div>
              </div>

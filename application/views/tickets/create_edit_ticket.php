<?php
if(isset($type) && $type=="question"){

}
?>
<div class="row">
<form id="create_edit_form"  action="<?=site_url('home/create_'.$type.'/'.$this->uri->segment(3))?>"  method="POST">
<?php if(validation_errors()){ ?>
<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php } ?>
                          <div class="col-sm-6" >
                            <label for="">Project</label>
                            <?php
                             $d = $this->master_model->get_projects_list();
                            ?>
                            <select class="form-control" name="project" form="create_edit_form" required>
                                <option value="">Select Project</option>
                                <?php

                                    foreach($d as $p){
                                ?>
                                <option value="<?=$p['pid']?>" ><?=$p['name']?></option>
                                <?php } ?>

                            </select>

                          </div>



                          <div class="col-sm-12">
                              <label for=""><span class="capitalize"><?=$type?></span> Title</label>
                            <input type="text" class="form-control" form="create_edit_form" name="title" Placeholder="Enter Title" value="" required>
                          </div>




                          <div class="col-sm-12">
                            <label for=""><span class="capitalize"><?=$type?></span> Description</label>


                            <textarea class="ckeditor" name="description" form="create_edit_form" id="editor1"></textarea>
                          </div>
<div class="col-sm-12">
                            <?php
                               $data['formid']="create_edit_form";
                               $this->load->view('blocks/attachFiles',$data);
                            ?>
        </div>


                          <pre class="pre">{{info}}</pre>
                          <div class="col-sm-12"><br>
                              <button type="submit" name="create_ticket"  form="create_edit_form" class="btn btn-success">Submit</button>
                          </div>

</form>
</div>

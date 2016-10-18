<link href="<?=base_url()?>assets2/css/pages/signin.css" rel="stylesheet" type="text/css">
<div class="container">
    <?php echo $this->session->flashdata('msg'); ?>
     <div class="row">
          <div class="account-container">
              <div class="content clearfix">
          <?php 
          $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform");
          echo form_open("login/index", $attributes);?>
          <fieldset>
               
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-3 col-sm-3 text-right">
                    <label for="txt_username" class="control-label">Email</label>
               </div>
               <div class="col-lg-9 col-sm-9">
                    <input class="form-control" id="txt_username" name="txt_username" placeholder="Email" type="text" value="<?php echo set_value('txt_username'); ?>" />
                    <span class="text-danger"><?php echo form_error('txt_username'); ?></span>
               </div>
               </div>
               </div>
               
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-3 col-sm-3 text-right">
               <label for="txt_password" class="control-label">Password</label>
               </div>
               <div class="col-lg-9 col-sm-9">
                    <input class="form-control" id="txt_password" name="txt_password" placeholder="Password" type="password" value="<?php echo set_value('txt_password'); ?>" />
                    <span class="text-danger"><?php echo form_error('txt_password'); ?></span>
               </div>
               </div>
               </div>
                              
               <div class="form-group">
               <div class="col-lg-12 col-sm-12 text-center">
                    <input id="btn_login" name="btn_login" type="submit" class="btn btn-default" value="Login" />
                    <input id="btn_cancel" name="btn_cancel" type="reset" class="btn btn-default" value="Cancel" />
               </div>
               </div>
          </fieldset>
          <?php echo form_close(); ?>
              </div> <!-- /content -->
              </div> <!-- /account-container -->
          
          </div>
     </div>
</div>
     
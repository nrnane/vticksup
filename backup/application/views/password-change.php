<?php 
//print_r($profile);

$this->load->view('blocks/flash_msg');
?>

<?php echo form_open_multipart('home/password/'.$this->uri->segment(3)); ?>
<div class="form-group">
                            <label for="" class="col-sm-4 control-label">Old Password</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="user[oldpassword]" Placeholder="Enter Old Password" value="" required>
                            </div>

                          </div>

                          <div class="form-group">
                            <label for="" class="col-sm-4 control-label">New Password</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" name="user[password]" Placeholder="Enter New Password" value="" required>
                            </div>
                          </div>

            

                         
                          <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <button type="submit" name="update_profile"  class="btn btn-success ladda-button"  >Update Your Password</button>
                            </div>

                          </div>
                            
                            
                            
<?php echo form_close(); ?>

                          
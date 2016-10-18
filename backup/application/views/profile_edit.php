<?php 
print_r($profile);

$this->load->view('blocks/flash_msg');
?>

<?php echo form_open_multipart('home/profile/'.$profile['uid']); ?>
<div class="form-group">
                            <label for="" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="user[name]" Placeholder="Enter User Name" value="<?=$profile['name']?>" required>
                            </div>

                          </div>

                          <div class="form-group">
                            <label for="" class="col-sm-4 control-label">User Email</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" name="user[email]" Placeholder="Enter User Email" value="<?=$profile['email']?>" required>
                            </div>
                          </div>

            

                          <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">Phone No</label>
                              <div class="col-sm-8">
                            <input type="text" class="form-control" name="user[phone]" Placeholder="Enter User Phone Number" value="<?=$profile['phone']?>">
                          </div>
                          </div>

                          <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">City</label>
                              <div class="col-sm-8">
                            <input type="text" class="form-control" name="user[city]" Placeholder="Enter City" value="<?=$profile['city']?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">Designation</label>
                              <div class="col-sm-8">
                            <input type="text" name="user[designation]" class="form-control" placeholder="Enter Designation" value="<?=$profile['designation']?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">Bio</label>
                              <div class="col-sm-8">
                            <textarea class="form-control" name="user[bio]"  Placeholder="Enter Your Bio"  rows="8" cols="40"><?=$profile['bio']?></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                              <label for=""  class="col-sm-4 control-label">Instagram Username</label>
                                <div class="col-sm-8">
                              <input type="text" class="form-control" name="user[instagram_username]" value="<?=$profile['instagram_username']?>">
                              </div>
                          </div>

                          <div class="form-group">

                            <!--<div class="col-sm-2" ng-bind-html="successMsg"></div>-->
                            <label for="" class="col-sm-4 control-label">Profile Pic</label>
                            <div class="col-sm-8">
                                <div style="width:300px" class="profilePicPhoto">
                                <?php $this->load->view('blocks/profilepic',$profile); ?>
                                    </div>
                                <div id="uploadProfilePic" class="btn btngf-getFile btn-primary">Upload Profile Pic</div>
                                
                                <input type="hidden" id="profilePicHidden" name="user[profilePic]" value="<?=$profile['profilePic']?>" />
                            </div>

                          </div>
                            <div class="clearfix"></div>
                          <div class="form-group">
                              <label for="" class="col-sm-4 control-label">Interested in</label>
                              <div class="col-sm-8">
                                  <label><input type="checkbox" name="user[i_male]" <?=($profile['i_male']==1)?'checked':''?> value="1">Male</label>
                                  <label><input type="checkbox" name="user[i_female]" <?=($profile['i_female']==1)?'checked':''?> value="1">Female</label>
                              </div>
                          </div>
                            <div class="clearfix"></div>


                          <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <button type="submit" name="update_profile"  class="btn btn-success ladda-button"  >Update Your Profile</button>
                            </div>

                          </div>
                            
                            
                            
<?php echo form_close(); ?>

                            <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="progress">
                    <div id="progressBar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <label class="col-md-2 control-label">Delete files:</label>
                <div class="col-md-10"><input type="text" id="tags" class="tags" value=""></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h4 id="title01"></h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <pre id="response01"></pre>
            </div>
        </div>
        
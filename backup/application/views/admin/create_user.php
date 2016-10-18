<?php $this->load->view('blocks/flash_msg');?>
<script src="<?=base_url()?>assets2/js/jquery.validate.min.js" charset="utf-8"></script>
<?php
$uid = isset($user)?$user['uid']:0;
?>
<form id="user_create" action="<?=site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3))?>" method="post">

                          <div class="col-sm-6">
                            <label for="">User type</label>
                            <select class="form-control" name="user[usertype]" <?=isset($user_profile_edit)?'disabled':''?> required >
                                <option value="">Select User Type</option>
                                <option value="0" <?=(isset($user['usertype'])&&$user['usertype']==0)?'selected':''?>>Admin</option>
                                <option value="1" <?=(isset($user['usertype'])&&$user['usertype']==1)?'selected':''?>>Manager</option>
                                <option value="2"<?=(isset($user['usertype'])&&$user['usertype']==2)?'selected':''?>>Support Team</option>

                            </select>
                          </div>

                          <div class="col-sm-6">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="user[name]" Placeholder="Enter User Name" value="<?=isset($user)?$user['name']:''?>" required>
                          </div>

                          <div class="col-sm-6">
                            <label for="">User Email</label>
                            <input type="text" class="form-control" ng-disabled="true" name="user[email]" Placeholder="Enter User Email" value="<?=isset($user)?$user['email']:''?>" required>
                          </div>

                          <div class="col-sm-6">
                            <label for="">Change Password</label>
                            <input type="text" class="form-control" name="user[password]" Placeholder="Enter New Password" value="">
                          </div>

                          <div class="col-sm-6">
                            <label for="">Phone No</label>
                            <input type="text" class="form-control" name="user[phone]" Placeholder="Enter User Phone Number" value="<?=isset($user)?$user['phone']:''?>">
                          </div>

                          <div class="col-sm-6">
                            <label for="">City</label>
                            <input type="text" class="form-control" name="user[city]" Placeholder="Enter City" value="<?=isset($user)?$user['city']:''?>">
                          </div>

                          <div class="col-sm-12">
                            <label for="">Address</label>

                            <textarea class="form-control" name="user[address]" Placeholder="Enter Address"  rows="8" cols="40"><?=isset($user)?$user['address']:''?></textarea>
                          </div>

    <?php if(!isset($user_profile_edit)){ ?>
    <div class="col-sm-12">

    <div class="clearfix"></div>
    <input type="hidden" name="uid" value="<?=isset($user)?$user['uid']:''?>" />
    <table class="table table-responsive table-bordered">
        <tr>
            <th>Project Name</th>
            <th>Assign</th>
        </tr>
        <?php
        if(!empty($projects)){
            foreach($projects as $p){ ?>
        <tr>
            <td><?=$p['name']?></td>
            <td>
                <input type="checkbox" name="project[<?=$p['pid']?>]"
                       <?php
                        $pid =  $p['pid'];
                            $sql = "SELECT * FROM projects_assign WHERE uid = $uid  AND pid = $pid AND assign = 1";
                           $pa =  $this->db->query($sql);
                           if($pa->num_rows()>0){
                               $da =$pa->row_array();
                           }
                           if(!empty($da)){


                           if($da['pid']==$p['pid']){ echo 'checked'; }
                           }
                       ?>
                       value="1" />

            </td>
        </tr>
        <?php }} ?>


    </table>
    <input type="hidden" name="project_assing" value="1" />
    </div>
    <div class="col-sm-12">
      Road Map
      <label for="road_map1">
        <input type="checkbox" name="user[roadmap_view]" <?php
         if(isset($user)){
            if($user['roadmap']==1 || $user['roadmap']==2){ echo 'checked'; }
         }
         ?>  value="yes" id="road_map1" />
        View
        </label>

        <label for="road_map2">
        <input type="checkbox" name="user[roadmap_edit]"
        <?php
         if(isset($user)){
            if($user['roadmap']==2){ echo 'checked'; }
         }
         ?>
         value="yes" id="road_map2" />
        Edit
        </label>
    </div>
    <?php } ?>



                          <div class="col-sm-12"><br/>
                              <button type="submit" class="btn btn-success" >
                                  <?php
                                    if($this->uri->segment(3)==0){
                                        echo 'Create User';
                                    }else{
                                        echo 'Update';
                                    }
                                  ?>
                              </button>
                          </div>


</form>

<pre>
    <?php print_r($user); ?>
</pre>
<?php if($_SESSION['usertype']==0 && $this->session->userdata("superadmin")==1){ ?>
<a href="<?=site_url('admin/all_users')?>" class="btn btn-warning fixedbotright"><i class="glyphicon glyphicon-chevron-left"></i>Return to All Users</a>
<?php } ?>
<script>
$("#user_create").validate();
</script>

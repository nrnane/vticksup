<?php $this->load->view('blocks/flash_msg');?>
<?php
$uid = $this->uri->segment(3);
?>
<form action="<?=site_url('admin/assign_project_to_users/'.$this->uri->segment(3))?>" method="post">

                         

                          <div class="col-sm-4">
                            <label for="">Name</label>
                            <?=isset($user)?$user['name']:''?>
                          </div>
                         <div class="col-sm-4">
                            <label for="">Email</label>
                            <?=isset($user)?$user['email']:''?>
                          </div>
                          <div class="col-sm-4">
                            <label for="">User Type</label>
                            <?=isset($user)?$user['usertype']:''?>
                          </div>
    <div class="clearfix"></div>
    <input type="hidden" name="uid" value="<?=isset($user)?$user['uid']:''?>" />
    <table class="table table-responsive table-bordered">
        <tr>
            <th>Project Name</th>
            <th>Assign</th>
        </tr>
        <?php 
            foreach($projects as $p){ ?>
        <tr>
            <td><?=$p['name']?></td>
            <td>
                <input type="checkbox" name="project[<?=$p['pid']?>]" 
                       <?php 
                           $pa =  $this->master_model->get_table('projects_assign','uid',$uid,'pid',$p['pid']);
                           if(!empty($pa)){
                           $pa = $pa[0];
                           
                           if($pa['pid']==$p['pid'] && $pa['assign']==1){ echo 'checked'; } 
                           }
                       ?>
                       value="1" />
                       <?php print_r($pa); ?>
            </td>
        </tr>
        <?php } ?>
        
        
    </table>


                          
                          <div class="col-sm-12"><br/>
                              <button type="submit" name="assign"  value="assign"class="btn btn-success" >
                                  Update
                              </button>
                          </div>

                        
</form>

<pre>
    <?php print_r($user); ?>
</pre>

<a href="<?=site_url('admin/all_users')?>" class="btn btn-warning fixedbotright"><i class="glyphicon glyphicon-chevron-left"></i>Return to All Users</a>
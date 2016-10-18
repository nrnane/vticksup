<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="glyphicon glyphicon-home"></i>
            <a href="<?=site_url()?>">Home</a>
        </li>
        <li class="active">View Users</li>
    </ul>
</div>
<div class="page-content">
    <div class="row">
        <div class="space-6"></div>
        <div class="col-sm-12">
            <div id="login-box" class="login-box visible widget-box no-border">
                <div class="widget-body">
                    <div class="widget-main">
                    <br>
                        <div class="space-16"></div>
<pre>
<?php
print_r($users);
?>
</pre>
<a href="<?=site_url('admin/create_user/0')?>" class="btn btn-primary">Create User</a>

    <table class="table table-bordered">
        <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>User Type</th>
               <th>Assined Projects</th>
              <th></th>
        </tr>
         <?php foreach($users as $data) { ?>
        <tr>
            <td><?=$data['name']?></td>

            <td><?=$data['email']?></td>

            <td><?=$data['phone']?> </td>

            <?php
                     switch ($data['usertype']){
                         case 0:
                             $team_name = 'Admin';
                             $team_class = 'label label-warning';
                             break;
                         case 1:
                             $team_name = 'Manager';
                             $team_class = 'label label-success';
                             break;
                         case 2;
                             $team_name = 'Support Team';
                              $team_class = 'label label-primary';
                             break;
                     }
              ?>

            <td ><span class="<?=$team_class?>"><?=$team_name?></span></td>
            <td>

                <?php
                    $passign = $this->master_model->get_table('projects_assign','uid',$data['uid'],'assign',1);
                    if(!empty($passign)){
                        foreach($passign as $p){
                            echo '<span class="label label-default">'.$this->master_model->get_column('projects','pid',$p['pid'],'name').'</span>';
                        }
                    }
                ?>

            </td>

            <td>
                <a href="<?=site_url('admin/create_user/'.$data['uid'])?>"  class="btn btn-success">Edit</a>
                <?php if($data['usertype']!=0){ ?>
                 <a href="<?=site_url('admin/delete_user/'.$data['uid'])?>" onclick="return confirm('Are you sure, you want to delete user ?');" class="btn btn-danger">Delete User</a>
                <?php } ?>
                <!--<a href="<?=site_url('admin/assign_project_to_users/'.$data['uid'])?>" class="btn btn-warning">Assign Project</a>-->
            </td>
        </tr>
        <?php } ?>
    </table>



    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.page-content -->

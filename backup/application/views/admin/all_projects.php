<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="glyphicon glyphicon-home"></i>
            <a href="<?=site_url()?>">Home</a>
        </li>
        <li class="active">View Projects</li>
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
<a href="<?=site_url('admin/create_project/0')?>" class="btn btn-primary">Create Project</a>
 
    <table class="table table-bordered">
        <tr>
              <th>Name</th>
              <th></th>
        </tr>
         <?php foreach($projects as $data) { ?>
        <tr>
            <td><?=$data['name']?></td>

            
            <td>
                <a href="<?=site_url('admin/create_project/'.$data['pid'])?>" class="btn btn-success">Edit</a>
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
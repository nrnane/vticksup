<div class="breadcrumbs row" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="glyphicon glyphicon-home"></i>
            <a href="<?= site_url('home/'.$type) ?>">Home</a>
        </li>

        <li class="active">
Dashboard
<!--<a href="<?= site_url('home/'.$type) ?>">All <span class="capitalize"><?=$type?></span></a>--></li>
    </ul>
</div>
<!--<pre>
<?php
$dad = $this->master_model->get_project_assigned_users(1);
print_r($dad);
?>
 </pre>-->
<h4 class="header blue lighter bigger row">
    <i class="icon-coffee green"></i>
    Total <span class="capitalize"><?=$type?> (<?= $totalRows ?>)</span>
</h4>

<?php if (isset($count)) { ?>
    <?php //print_r($count); ?>
    <ul class="list-group clearfix row" >
        <?php
        if (!empty($count)) {
            foreach ($count as $item) {
                ?>
                <li class="list-group-item col-sm-4 bold text-center label label-<?= $item['class_name'] ?>">
                    <a href="<?= site_url('home/tickets_by_status/' . $item['status_id'].'/'.$type) ?>" class=" label label-<?= $item['class_name'] ?>">

            <?= $item['status_name'] ?>

                    </a>
                    <span class="badge"><?= $item['count'] ?></span>
                </li>
            <?php }
        }
        ?>
    </ul>
<?php } ?>

<?php $this->load->view('blocks/flash_msg'); ?>

<div class="row profile clearfix">
    <form name="searchForm" id="TicketSearchForm" action="<?=site_url('ajax/searchTickets')?>" method="POST">
      <?php
      if(isset($searchData)){
        //print_r($searchData);
      }
       ?>
        <div class="row">
            <div class="col-sm-2">
                <label>Project</label>
                <?php
                $d = $this->master_model->get_projects_list();
                ?>

                <select class="form-control" name="search[project]" id="searchProject">
                    <option value="100">Select Project</option>
                    <?php
                    if (!empty($d)) {
                        foreach ($d as $p) {
                            ?>
                            <option value="<?= $p['pid'] ?>"
                              <?php
                              if(isset($searchData)){
                                if($searchData['project']==$p['pid']){ echo 'selected'; }
                              }
                              ?>
                              ><?= $p['name'] ?></option>
    <?php }
} ?>

                </select>
                <input type="hidden" name="search[method_name]" value="<?=$type?>" />
            </div>


            <div class="col-sm-3">
                <label>Status</label>
                <?php
                $d = $this->master_model->get_table($tbl_type.'_s');
                ?>
                <select name="search[status]" class="form-control" id="searchStatus">
                    <option value="100">All</option>
                    <?php
                    foreach ($d as $p) {
                        ?>
                        <option value="<?= $p['status_id'] ?>"
                          <?php
                          if(isset($searchData)){
                            if($searchData['status']==$p['status_id']){ echo 'selected'; }
                          }
                          ?>
                          ><?= $p['status_name'] ?></option>
<?php } ?>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Date Range</label>
                 <div class="input-daterange" id="datepicker" >
                    <?php
                        $curDate = date("Y-m-d");
                        $curDateTime = time();
                        $twoWeeksBack = $curDateTime-1209600;
                        $twoWeeksBackDate = date("Y-m-d",$twoWeeksBack);

                        if(isset($searchData)){
                          //if($searchData['status']==$p['status_id']){ echo 'selected'; }
                          $curDateTime = $searchData['toDate'];
                          $twoWeeksBackDate = $searchData['fromDate'];
                        }

                    ?>
                     <input type="text" id="fromDate" class="input-small form-control pull-left" placeholder="From date" name="search[fromDate]" value="<?=$twoWeeksBackDate?>" style="width:48%" />

                     <input type="text" id="toDate" class="input-small form-control pull-left" placeholder="To date" name="search[toDate]" value="<?=$curDate?>" style="width:48%" />
            </div>
            </div>
            <div class="col-sm-2">
                <input type="hidden" name="search[type_n]" value="<?=$type_n?>" />
                <label>Search</label>
                <input type="text" name="search[search]" class="form-control pull-left" placeholder="Search" id="searchSearch"
                <?php
                if(isset($searchData)){
                  if($searchData['search']!=''){ echo 'value="'.$searchData['search'].'"'; }
                }
                ?>
                 />

            </div>
             <div class="col-sm-1"><br/>
                 <input type="submit" value="Search" class="btn btn-success" />
             </div>
            <div class="col-sm-1"><br/>
                <?php if(USERTYPE==0 || USERTYPE==1){ ?>
                <a href="<?=site_url('home/create_ticket/0')?>" class="btn btn-primary pull-right "  popover="Create a Ticket" popover-trigger="mouseenter">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
                <?php } ?>

            </div>
            <div class="clearfix"></div>

        </div>

    </form>
<?php //print_r($results); ?>

    <div id="divID">
        <div class="row" style="margin-top:20px;">

        </div>
        <table class="table table-bordered" id="rounded-corner">
            <tr>
                <th><span class="capitalize"><?=substr($type,0,-1)?></span> Id</th>

                <th width="45%">Title</th>
                <th>Project</th>
                <th>Created by</th>
                <th>Created</th>

                <th>Status</th>

                <th>Options</th>
            </tr>
            <?php
            if (!empty($results)) {
                foreach ($results as $data) {
                    ?>
                    <tr>
                        <td><span class="capital"><?=substr($type,0,-1)?></span>-<?= $data->id ?></td>



                        <td><?= $data->title ?>
                            <?php if ($data->attach_count > 0) { ?>
                                <i class="glyphicon glyphicon-paperclip" title="<?= $data->attach_count ?> Attachments"></i>
        <?php } ?>
                        </td>
                        <td><?= $data->project ?></td>
                        <td><?= $data->name ?></td>
                        <td><?= $data->date ?></td>
                        <td>
                            <span class="text-<?= $data->class_name ?>"><?= $data->status_name ?></span>
                            </br>
                            <?php
                            if ($data->last_status_time != NULL) {
                                echo $this->master_model->time_ago($data->last_status_time);
                            } else {

                                echo $this->master_model->time_ago($data->time);
                            }
                            ?>
                        </td>


                        <td>
                            <a href="<?= site_url('home/view_'.substr($type,0,-1).'/' . $data->id) ?>" class="btn btn-success">View</a>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo '<td colspan="8">No Tickets Found </td>';
            }
            ?>
        </table>
       <div class="row" style="margin-top:20px;">
            <div class="col-sm-6"><?php echo $links; ?></div>
            <div class="col-sm-6 text-right">Total <span class="capitalize"><?=$type?></span>: <?= $totalRows ?></div>
        </div>
    </div>


</div>

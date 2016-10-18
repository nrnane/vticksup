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
            <div class="col-sm-6 text-right">Total <span class="capitalize"><?=$type?></span>: <?=$totalRows ?></div> 
        </div>
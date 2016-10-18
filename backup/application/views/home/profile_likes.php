<pre><?php
    //print_r($profile_likes);
?>
</pre>
<h1>Profile <?=$type?> Persons</h1>
<table class="table table-bordered">
    <tr>
        <th>Person Name</th>
        <th>Type</th>
        <th>Time ago</th>
        <th>Date</th>
    </tr>
    <?php foreach ($profile_likes as $data) { ?>
    <tr>
        <td><a href="<?=base_url('user/'.$data['username'])?>"><?=$data['name']?></a></td>
        <td><?=($data['type']==1)?"Liked":''?></td>
        <td><?php echo $this->master_model->time_ago($data['time']); ?></td>
        <td><?php echo $this->master_model->time_to_date($data['time']); ?></td>
    </tr>
    <?php } ?>
    
</table>
<pre><?php
    print_r($profile_likes);
?>
</pre>
<table class="table table-bordered">
    
    <?php foreach ($profile_likes as $data) { ?>
    <tr>
        <td><?=$data['uid']?></td>
        <td><?=$data['type']?></td>
        <td><?php echo $this->master_model->time_ago($data['time']); ?></td>
    </tr>
    <?php } ?>
    
</table>


 <table border="0" cellpadding="10" cellspacing="1" width="100%" class="table table-bordered table-striped">
        <tr class="listheader">
        <td>Project</td>
        <td>Module</td>
        <td>Timeline</td>
        <td></td>
        </tr>
        <?php
        $i=0;
        foreach ($roadmap as $row) {
          //print_r($row);
        ?>
        <tr class="">
        <td><?php echo $row["name"]; ?></td>
        <td><?php echo $row["module"]; ?></td>
        <td><?php echo $row["time_line"]; ?></td>
        <td>
          <?php
          /*
          if($_SESSION['roadmap']==2){
           ?>
           <a href="javascript:;show_dialog('<?php  ?>edit_roadmap.php?id=<?php echo $row["id"]; ?>','Edit Road Map',600,300,true);" class="btn btn-primary">
        <span class="ion-edit"></span> Edit</a>

          <a href="delete_roadmap.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure Delete this Road Map ? ')"  class="btn btn-danger">Delete</a>
          <?php }*/  ?>
        </td>
        </tr>
        <?php
        $i++;
        }
        ?>
        </table>

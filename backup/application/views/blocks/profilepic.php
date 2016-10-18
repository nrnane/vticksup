
 <?php
    $url = $profilePic;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
           $url = "uploads/" . $url;
           $url = base_url($url);
    }
?>
<img src="<?=$url?>" class="media-object profilePic"  alt="">


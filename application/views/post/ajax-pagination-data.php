<?php if(!empty($posts)): foreach($posts as $post): ?>
<li>
    <p><b>Title:</b>&nbsp;<?php echo $post['title']?></p>
    <p><b>Content:</b>&nbsp;<?php echo $post['description']?></p>
    <p><b>Created:</b>&nbsp;<?php echo $post['date']?></p>
</li>
<?php endforeach; else: ?>
<li class="err_msg">Post(s) not available.</li>
<?php endif; ?>
<?php echo $this->ajax_pagination->create_links(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
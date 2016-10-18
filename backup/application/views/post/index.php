<h1>Posts</h1>
<div id="container">
    <ul class="list" id="postList">
        <?php if(!empty($posts)): foreach($posts as $post): ?>
        <li>
            <p><b>Title:</b>&nbsp;<?php echo $post['title']?></p>
            <p><b>Content:</b>&nbsp;<?php echo $post['content']?></p>
            <p><b>Created:</b>&nbsp;<?php echo $post['created']?></p>
        </li>
        <?php endforeach; else: ?>
        <li class="err_msg">Post(s) not available.</li>
        <?php endif; ?>
        <?php echo $this->ajax_pagination->create_links(); ?>
    </ul>
</div>
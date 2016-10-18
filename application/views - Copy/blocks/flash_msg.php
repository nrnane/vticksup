<?php
if($this->session->flashdata('msg')):
    echo '<div class="alert alert-success">'.$this->session->flashdata('msg').'</div>';
endif;
?>
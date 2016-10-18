<?php
$img_ext = array("jpg", "jpeg", "png", "gif","bmp");
/*echo '<pre>';
        print_r($_POST);
        echo '</pre>'; */

?>
<style>
    .col-sm-12 {
    border-bottom: 1px solid #d2d2d2;
}
</style>

<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="glyphicon glyphicon-home"></i>
            <?php
            if($this->uri->segment(2)=='view_question'){
                $t_url = 'questions';
            }elseif ($this->uri->segment(2)=='view_ticket') {
                $t_url = 'tickets';
            }
            ?>
            <a href="<?=site_url('home/'.$t_url)?>">Home</a>
        </li>
        <li class="active"><i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;View Ticket</li>
    </ul>
</div>
<br/>

<div class="whitebg">

<?php $this->load->view('blocks/flash_msg');?>
                        <div class="col-sm-12">
                            <div class="col-sm-6"><label for="">Ticket Id</label></div>
                            <div class="col-sm-6">TICKET-<?=$ticket->id?></div>
                        </div>

                        <div class="col-sm-12 well">
                          <div class="col-sm-12 clearfix">
                          <label for="">Title</label><br>

                          <?=$ticket->title?>
                          </div>
                          <div class="col-sm-12">
                          <label for="">Description</label><br>
                          <div class="desc ">
                              <?=$ticket->description?>
                          </div>
                          <div class="clearfix"></div>
                          <?php if($ticket->attach_count>0){

                              ?>
                          <div class="attachments">
                            <hr/>
                            <label><i class="glyphicon glyphicon-paperclip" title="<?=$ticket->attach_count?> Attachments"></i> Attachments</label><br/>
                            <?php foreach($attachements as $item){
                                $ext = pathinfo($item['img_name'], PATHINFO_EXTENSION);
                                if(in_array($ext, $img_ext)){
                                ?>
                                <a href="<?=base_url('uploads/'.$item['img_name'])?>" data-toggle="lightbox" class="pull-left" style="width:150px;height:150px; margin-right:5px;">
                                  <img src="<?=base_url('uploads/'.$item['img_name'])?>" width="150" height="150" alt="" />
                                </a>
                                <?php }else{
                                    echo '<a href="'.base_url('uploads/'.$item['img_name']).'" target="_blank">'.$item['img_name'].'</a>';
                                }


                                } ?>
                              </div>
                           <?php } ?>
                              <div class="clearfix"></div>
                          </div>
                        </div>

                        <div class="col-sm-12">
                          <div class="col-sm-6"><label for="">Project Name</label></div>
                          <div class="col-sm-6"> <?=$ticket->project?></div>



                        </div>


                        <div class="col-sm-12">
                            <div class="col-sm-6"><label for="">Created By</label></div>
                            <div class="col-sm-6"> <?=$ticket->name?></div>


                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6"><label for="">Created</label></div>
                            <div class="col-sm-6">
                                 <?=$ticket->date?>,
                                  <?php echo $this->master_model->time_ago($ticket->time);?>
                            </div>


                        </div>






                            <div class="col-sm-12">
                              <div class="col-sm-6"><label for="">Status </label>
                              <span class="shide"><?=$ticket->status?></span>
                              </div>
                              <div class="col-sm-6">


                                 <span class="info info-<?=$ticket->class_name?>"><?=$ticket->status_name?></span>

                              </div>

                            </div>

                            <div class="clearfix"></div>
                          </div><!--End Whitebg-->


                          <!--COnverjations start-->

                            <div class="clearfix"></div>
                            <br/>
                            <div class="well">


                            <h2>Conversations</h2>
                            <?php if(isset($ticket_status) && !empty($ticket_status)){
                                foreach($ticket_status as $ts){
                                ?>
                            <div class="row" >
                              <div class="col-sm-1">
                              <div class="thumbnail">
                              <img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">
                              </div><!-- /thumbnail -->
                              </div><!-- /col-sm-1 -->

                              <div class="col-sm-10">
                              <div class="panel panel-default">
                              <div class="panel-heading">
                              <strong>
                                  <?php echo $ts->name;
                                  (UID==$ts->uid)?'(You)':'';
                                  ?>
                                  </strong>&nbsp; <span class="text-muted">
								<?php
                                  if($ts->reopen==1){
                                    echo 'Re-Opened ';
                                  }else{
                                    echo 'Replied ';
                                  }
                                  ?>
								  <?php echo $this->master_model->time_ago($ts->time);?> </span>
                                  <?php 
                                  if($ts->reopen==1){ echo '- <strong style="color:red;">Ticket Re-Opened</strong>';}
                                  ?>
                              <br>
                                  <span class="<?=$ts->class_name?>"><i><?=$ts->status_name?></i></span>
                              </div>
                              <div class="panel-body" >
                                <div class="" >
                                    <?=$ts->message?>
                                </div>
                                 <?php
                                 if($ts->attach_count>0){
                                    $this->db->where('group_id',$ts->attachments_id);
                                    $q = $this->db->get('attachments');
                                    $d = $q->result();
                                            foreach($d as $a){
                                 ?>
                                  <a  href="<?=base_url('uploads/'.$a->img_name)?>" target="_blank" class="pull-left" >
                                   <i class="glyphicon glyphicon-paperclip"></i> <?=$a->img_name?>
                                 </a>
                                 <?php }
                                  } ?>
                                 <div class="clearfix"></div>
                              </div><!-- /panel-body -->

                              </div><!-- /panel panel-default -->
                            </div><!-- /col-sm-10 -->

                            </div><!-- /row -->
                            <?php } }else{ ?>
                            <div class="no-conversations" >
                                <p><i>Conversations not yet started</i></p>
                            </div>
                            <?php } ?>
                              </div>


                             <?php
                            if($this->uri->segment(2)=="view_ticket"){
                             ?>
                            <form id="create_edit_form" action="<?=site_url('home/view_ticket/'.$this->uri->segment(3))?>" method="POST">
                            
                            <div class="col-sm-12 replay_section clearfix">
                            <?php if($ticket->status!=3){ ?>
                              <h2><i class="glyphicon glyphicon-edit"></i> Write Your Reply</h2>
                              <!--<textarea class="form-control" rows="8" cols="40" ng-model="ticket.message"></textarea>-->
                              <textarea class="ckeditor" name="ticket[message]" form="create_edit_form" id="editor1"></textarea>
                              <input type="hidden" name="ticket[title]" form="create_edit_form" value="<?=$ticket->title?>" />
                              <input type="hidden" name="ticket[uid]" form="create_edit_form" value="<?=$ticket->uid?>" />
                              <input type="hidden" name="ticket[status]" form="create_edit_form" value="<?=$ticket->status?>" />
                              <input type="hidden" name="ticket[id]" form="create_edit_form" value="<?=$ticket->id?>" />
                              <input type="hidden" name="ticket[pid]" form="create_edit_form" value="<?=$ticket->pid?>" />
                              <?php
                              $data['formid']="TicketStatusForm";
                               $this->load->view('blocks/attachFiles',$data);
                            ?>

                              <?php 

                              if(USERTYPE ==0){
                              if($ticket->status==0 || $ticket->status==4){
                                  ?>
                              <input type="submit" name="approve" value="Approve" form="create_edit_form"  class="btn btn-success" />

                              <?php } 
                              
                              }?>
                              <input type="submit" name="reply" value="Reply" form="create_edit_form" class="btn btn-primary" />
                               <?php if(USERTYPE ==0 || USERTYPE ==2){
                                if($ticket->status!=0 && $ticket->status!=6){    ?>
                              <input type="submit" name="completed" value="Completed" form="create_edit_form" class="btn btn-warning" />
                               <?php } } ?>
                               <?php if(USERTYPE !=2){
                                 if($ticket->reply_count!=0){   ?>
                              <input type="submit" name="closeticket" value="Close Ticket" form="create_edit_form" class="btn btn-danger" />
                               <?php } } ?>

                            
                            <?php }else{ //if ticket closed Re-open
                               if(USERTYPE ==0 || USERTYPE ==1){ ?>
                                <h2><i class="glyphicon glyphicon-edit"></i> Write Your Reply</h2>
                              <!--<textarea class="form-control" rows="8" cols="40" ng-model="ticket.message"></textarea>-->
                              <textarea class="ckeditor" name="ticket[message]" form="create_edit_form" id="editor1"></textarea>
                              <input type="hidden" name="ticket[title]" form="create_edit_form" value="<?=$ticket->title?>" />
                              <input type="hidden" name="ticket[uid]" form="create_edit_form" value="<?=$ticket->uid?>" />
                              <input type="hidden" name="ticket[status]" form="create_edit_form" value="<?=$ticket->status?>" />
                              <input type="hidden" name="ticket[id]" form="create_edit_form" value="<?=$ticket->id?>" />
                              <input type="hidden" name="ticket[pid]" form="create_edit_form" value="<?=$ticket->pid?>" />
                                 <?php
                              $data['formid']="TicketStatusForm";
                               $this->load->view('blocks/attachFiles',$data);
							   if(USERTYPE ==0 ){
                            ?>
									
                                 <input type="submit" name="reopen" value="Re-Open" form="create_edit_form"  class="btn btn-success" />
                               <?php }
							   }

                            } ?>
                            </div>
                            </form>
                            

                            <?php if($ticket->status==3){
                              echo '<a href=""';
                            }?>
                            <a href="<?=site_url('home/tickets')?>" class="btn btn-warning fixedbotright"><i class="glyphicon glyphicon-chevron-left"></i>Return to All Tickets</a>

                            <?php } ?>

                            <!--Question COmment Section Start -->

                            <?php
                            if($this->uri->segment(2)=="view_question"){
                            if($ticket->status!=3){ ?>
                            <form id="create_edit_form" action="<?=site_url('home/view_question/'.$this->uri->segment(3))?>" method="POST"> </form>
                            <div class="col-sm-12 replay_section clearfix">

                              <h2><i class="glyphicon glyphicon-edit"></i> Write Your Reply</h2>
                              <!--<textarea class="form-control" rows="8" cols="40" ng-model="ticket.message"></textarea>-->
                              <textarea class="ckeditor" name="ticket[message]" form="create_edit_form" id="editor1"></textarea>
                              <input type="hidden" name="ticket[title]" form="create_edit_form" value="<?=$ticket->title?>" />
                              <input type="hidden" name="ticket[uid]" form="create_edit_form" value="<?=$ticket->uid?>" />
                              <input type="hidden" name="ticket[status]" form="create_edit_form" value="<?=$ticket->status?>" />
                              <input type="hidden" name="ticket[id]" form="create_edit_form" value="<?=$ticket->id?>" />
                              <input type="hidden" name="ticket[pid]" form="create_edit_form" value="<?=$ticket->pid?>" />
                              <?php
                              $data['formid']="TicketStatusForm";
                               $this->load->view('blocks/attachFiles',$data);
                            ?>

                              <input type="submit" name="reply" value="Reply" form="create_edit_form"  class="btn btn-success" />


                            <?php if(USERTYPE ==0 || USERTYPE ==1){
                                if($ticket->status!=2){    ?>
                              <input type="submit" name="completed" value="Completed" form="create_edit_form" class="btn btn-warning" />
                               <?php } } ?>
                            </div>
                            <?php } ?>

                            <a href="<?=site_url('home/questions')?>" class="btn btn-warning fixedbotright"><i class="glyphicon glyphicon-chevron-left"></i>Return to All Questions</a>

                            <?php } ?>

<pre style="height:300px;"><?php
print_r($friends);
?>
</pre>


<div class="col-sm-12">
            <div class="filter">
                <div class="col-sm-2">
                     Seeking
                    <select class="form-control" name="">
                        <option>Female</option>
                        <option>Male</option>
                        <option>All</option>
                    </select>

                </div>
                
                <div class="col-sm-3">
                    Age
                  <table width="100%">
                    <tr>
                      <td>
                        <select class="form-control" name="">
                            <option>From</option>
                            <option>19</option>
                            <option>20</option>
                        </select>
                      </td>
                      <td>
                        <select class="form-control" name="">
                            <option>To</option>
                            <option>19</option>
                            <option>20</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </div>
                
                <div class="col-sm-2">
                    Country

                  <select class="form-control" name="">
                      <option>Any</option>
                      <option>India</option>
                      <option>20</option>
                  </select>
                </div>
                
                <div class="col-sm-2"> State

                  <select class="form-control" name="">
                      <option>Any</option>
                      <option>India</option>
                      <option>20</option>
                  </select></div>
                <div class="col-sm-2">
                    City

                  <select class="form-control" name="">
                      <option>Any</option>
                      <option>India</option>
                      <option>20</option>
                  </select>
                </div>
                 
                <div class="col-sm-1">
                    <input type="submit" name="name" value="Search" class="btn btn-success">
                </div>
               

            </div>
          </div>
          <div class="col-sm-12">
              
              <?php
                if(isset($friends)){
                    foreach ($friends as $fri){
              ?>
            <div class="col-sm-2 person_block well" id="friend_block_<?=$fri['uid']?>"  style="margin-right: 10px;">
              <div class="p20">
                <span class="curser top_left like_btn" id="like_<?=$fri['uid']?>" title="Like">
                    <i class="ion-heart <?=($fri['liked']==1)?'liked':''?>"></i>
                </span> 
                <a href="<?=base_url('user/'.$fri['username'])?>">
                    <?php $this->load->view('blocks/profilepic',$fri); ?>
                </a>
                <h4 class="user_name"><i class="icon-user"></i><?=$fri['name']?></h4>


                <div class="btn-group">
                    <a class="dropdown-toggle btn btn-mini btn-success " data-toggle="dropdown" 
                       href="#" role="button" aria-haspopup="true" aria-expanded="false"> 
                        <span class="friend_<?=$fri['uid']?>">
                        <?=($fri['friend_accept']!=NULL)?$fri['friend_accept']:'Add as Friend'?> 
                        </span>
                        <span class="caret"></span>
                       
                    </a>
                    
                  <ul class="dropdown-menu"  id="friend_ul_<?=$fri['uid']?>">
                       <?php if($fri['accept']=='' || $fri['accept']==NULL){ ?>
                      <li><a class="send_friend_request" id="friend_<?=$fri['uid']?>" >Send a friend request</a></li>
                      <?php } ?>
                      <?php if($fri['accept']==1){ //Friend Accept = 1 ?>
                      <li><a class="accept_friend_request" id="friend_<?=$fri['uid']?>" >Unfriend</a></li>
                      <?php } ?>
                      
                        <?php if($fri['accept']==0 && $fri['friend_two']==UID){ //Friend Accept = 1 ?>
                         <li><a class="accept_friend_request" id="friend_<?=$fri['uid']?>">Accept</a></li>
                         <li><a class="send_friend_request" id="friend_<?=$fri['uid']?>">Reject</a></li>
                     <?php } ?>
                          
                          <?php if($fri['accept']==0 && $fri['friend_one']==UID){ //Friend Accept = 1 ?>
                         <li><a class="send_friend_request" id="friend_<?=$fri['uid']?>" >Cancel friend request</a></li>
                          <?php } ?>
                  </ul>
                      
                    
                </div>
                <div class="clearfix"></div>
              </div>

              <div class="location_details">
                <i class="icon-location-arrow"></i> 
                <?=$fri['city']?>, 
                       <?=$fri['state']?>, 
                       <?=$fri['country']?> - 
                       <?=$fri['zipcode']?>
              </div>
            </div>
              <?php
                }
                }
              ?>
              
        </div> <!-- ENd Col-sm-9-->
        
        
        <div class="clearfix"></div>
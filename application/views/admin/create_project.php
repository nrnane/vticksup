<?php $this->load->view('blocks/flash_msg');?>
<form action="<?=site_url('admin/create_project/'.$this->uri->segment(3))?>" method="post">

                         

                          <div class="col-sm-6">
                            <label for="">Project Name</label>
                            <input type="text" class="form-control" name="project[name]" Placeholder="Enter Project Name" value="<?=isset($project)?$project['name']:''?>" required>
                          </div>

                         



                          
                          <div class="col-sm-12"><br/>
                              <button type="submit" class="btn btn-success" >
                                  <?php 
                                    if($this->uri->segment(3)==0){
                                        echo 'Create Project';
                                    }else{
                                        echo 'Update';
                                    }
                                  ?>
                              </button>
                          </div>

                        
</form>

<pre>
    <?php print_r($user); ?>
</pre>
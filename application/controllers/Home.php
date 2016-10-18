<?php
class Home extends CI_Controller{
     public function __construct()
     {
          parent::__construct();


          if( trim($this->session->userdata('uid')) == ''){
                    redirect("login");
            }
             $this->load->model('master_model');
            $this->load->library("pagination");

     }

     function _remap($method_name){
            if($method_name=='index'){
              redirect('home/tickets');
            }
             if(!method_exists($this, $method_name)){
                 //$method_name='tickets';
                $this->tickets($method_name);
             }
             else{
                $this->{$method_name}();
             }
         }



     public function tickets($method_name='tickets'){
           $config = array();
           //echo $method_name;

           if($method_name==='tickets'){

               $type=0;
               $data['type_n']=0;
               $data['tbl_type']='tickets';
           }elseif($method_name=='questions'){

               $type=1;
               $data['type_n']=1;
               $data['tbl_type']='question';
           }
        $config["base_url"] = site_url() . "/home/".$method_name;
        $totalRows = $this->master_model->fetch_tickets($type,'','',1);
        $config["total_rows"] = $totalRows; //1 for count
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        //$config['use_page_numbers']  = TRUE;


        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["results"] = $this->master_model->
            fetch_tickets($type,$config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

         $data['count'] = $this->master_model->getTicketsCount($type);
         $data['totalRows'] = $totalRows;
         $data['type'] = $method_name;
         $data['page']='homepage';
	 $this->load->view('comman/template1',$data);
     }



     public function tickets_by_status(){


        $status_id = $this->uri->segment(3);
        $method_name = $this->uri->segment(4);
        if($method_name=='tickets'){

               $type=0;
               $data['type_n']=0;
               $data['tbl_type']='tickets';
           }elseif($method_name=='questions'){
               $type=1;
               $data['type_n']=1;
               $data['tbl_type']='question';
           }

        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $config["per_page"] = 10;

        //echo $status_id;
        $data["results"] = $this->master_model->fetch_tickets($type,$config["per_page"],$page,'',$status_id);

          $config = array();
        $config["base_url"] = site_url() . "/home/tickets_by_status/".$this->uri->segment(3).'/'.$method_name;
        $config["total_rows"] = $totalRows = $this->master_model->fetch_tickets($type,'','',1,$status_id); //1 for count
        $data['totalRows'] = $totalRows;
        $config["uri_segment"] = 5;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();



        $data['count'] = $this->master_model->getTicketsCount($type);



         $data['type'] = $method_name;
         $data['page']='homepage';

	 $this->load->view('comman/template1',$data);
     }



     public function view_ticket() {
        $tid = $this->uri->segment(3);
        //print_r($this->session->userdata());

        $this->master_model->ticket_status_update();

         $ticket = $this->master_model->getalltickets('t.id',$tid);
         $data['ticket'] = $ticket[0];
         if($ticket[0]->attach_count>0){
            $data['attachements'] = $this->master_model->get_table('attachments','group_id',$ticket[0]->attachments_id);
         }

           $ts = $this->master_model->ticketStatus('ts.tid', $ticket[0]->id);
            $data['ticket_status']= $ts;



         $data['page']='tickets/ticket_by_id';

	 $this->load->view('comman/template1',$data);
     }

     public function view_question() {
        $tid = $this->uri->segment(3);
        //print_r($this->session->userdata());

        $this->master_model->question_status_update();

         $ticket = $this->master_model->getalltickets('t.id',$tid,1);
         $data['ticket'] = $ticket[0];
         if($ticket[0]->attach_count>0){
            $data['attachements'] = $this->master_model->get_table('attachments','group_id',$ticket[0]->attachments_id);
         }

           $ts = $this->master_model->ticketStatus('ts.tid', $ticket[0]->id,1);
            $data['ticket_status']= $ts;



         $data['page']='tickets/ticket_by_id';

	 $this->load->view('comman/template1',$data);
     }

     public function create_ticket() {
       $this->load->helper(array('form', 'url'));
       $this->load->library('form_validation');

         $id = $this->uri->segment(3);
         if($this->input->post('title')){
             //$ticket = $this->input->post('ticket');
             $this->form_validation->set_rules('project', 'Project', 'required');
             $this->form_validation->set_rules('title', 'Title', 'required');
             $this->form_validation->set_rules('description', 'Description', 'required');

             if ($this->form_validation->run() == FALSE)
          		{

          		}
          		else
          		{
          			//if validation ok
                if($attach = $this->input->post('attach')){
                    $group_id = UID.'_'.time();
                    foreach($attach as $key=>$value){

                        $adata = array(
                            'img_name'=>$value,
                            'uid'=>UID,
                            'group_id'=>$group_id
                        );
                        $this->master_model->insert_table('attachments',$adata);
                    }
                }else{
                    $group_id = '';
                }

                if(USERTYPE == 0){ //if admin create ticket status will be approved wating for support
                  $ticket_status = 1;
                  $touser = "Support Team";
                  $tomails = 2;
                   $status = "Approved (Waiting from Support)";
                }else{
                    $ticket_status = 0;
                    $touser = "Admin";
                   $tomails = 1;
                   $status = "Waiting for Approval";
                }

                $data = array(
                    'isQuestion'=>0,
                    'project'=>$this->input->post('project'),
                    'title'=>$this->input->post('title'),
                    'description'=>$this->input->post('description'),
                    'status'=>$ticket_status,
                    'attachments_id'=>$group_id,
                    'uid'=>UID,
                    'time'=>time()
                );

                $project_id = $this->input->post('project');

                if($insert_id = $this->master_model->insert_table('tickets',$data)){
                     $this->session->set_flashdata('msg', 'Ticket Created Successfully');
                     $this->master_model->sendmail("",$tomails,$touser,USERNAME,USEREMAIL,"Ticket-".$insert_id." Created - ".$this->input->post('title').", ".USERNAME,
            "<b>Ticket Id:</b>Ticket-".$insert_id."<br/><br/><b>Ticket Title:</b>".$this->input->post('title'),"<b>Ticket Description:</b>".$this->input->post('description'),"<b>Status:</b>".$status,site_url("home/view_ticket/".$insert_id),$project_id);

                     redirect('home/tickets');
                }

          		}//End Validation




         }
         $data['type']="ticket";
         $data['page']='tickets/create_edit_ticket';
	        $this->load->view('comman/template1',$data);
     }




     public function create_question() {
       $this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');

         $id = $this->uri->segment(3);
         if($this->input->post('title')){
             //$ticket = $this->input->post('ticket');

            $this->form_validation->set_rules('project', 'Project', 'required');
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            if ($this->form_validation->run() == FALSE)
        		{

        		}
        		else
        		{

             if($attach = $this->input->post('attach')){
                 $group_id = UID.'_'.time();
                 foreach($attach as $key=>$value){

                     $adata = array(
                         'img_name'=>$value,
                         'uid'=>UID,
                         'group_id'=>$group_id
                     );
                     $this->master_model->insert_table('attachments',$adata);
                 }
             }else{
                 $group_id = '';
             }

             if(USERTYPE == 0){ //if admin create ticket status will be approved wating for support
               $ticket_status = 1;
               $touser = "Support Team";
               $tomails = 2;
                $status = "Approved (Waiting from Support)";
             }else{
                 $ticket_status = 0;
                 $touser = "Admin";
                $tomails = 1;
                $status = "Waiting for Approval";
             }

             $data = array(
                 'isQuestion'=>1,
                 'project'=>$this->input->post('project'),
                 'title'=>$this->input->post('title'),
                 'description'=>$this->input->post('description'),
                 'status'=>$ticket_status,
                 'attachments_id'=>$group_id,
				 'reopen'=>0,
                 'uid'=>UID,
                 'time'=>time()
             );

            $project_id = $this->input->post('project');

             if($insert_id = $this->master_model->insert_table('tickets',$data)){
                  $this->session->set_flashdata('msg', 'Question Created Successfully');
                  $this->master_model->sendmail("",$tomails,$touser,USERNAME,USEREMAIL,"Question-".$insert_id." Created - ".$this->input->post('title').", ".USERNAME,
         "<b>Question Id:</b>Question-".$insert_id."<br/><br/><b>Question Title:</b>".$this->input->post('title'),"<b>Question Description:</b>".$this->input->post('description'),"<b>Status:</b>".$status,site_url("home/view_question/".$insert_id),$project_id);

                  redirect('home/questions');
             }

             	}//End Validation

         }
         $data['type']="question";
         $data['page']='tickets/create_edit_ticket';
	 $this->load->view('comman/template1',$data);
     }





     public function profile() {
        $id = $this->uri->segment(3);

        if($this->session->userdata("uid")!=$id && $this->session->userdata("superadmin")==0){
          redirect("home/tickets");
        }

         if($this->input->post('user')){

                $user = $this->input->post('user');
                if($this->input->post('user')['password']){
                    $password= $this->input->post('user')['password'];
                }else{
                    $this->db->where('uid',$id);
                    $q = $this->db->get('users');
                    if($q->num_rows()>0){
                       $d = $q->row_array();
                    }
                    $password = $d['password'];
                }
                $data = array(
                     'name'=>$user['name'],
                     'email'=>$user['email'],
                     'phone'=>$user['phone'],
                     'password'=>$password,
                     'address'=>$user['address'],
                     'city'=>$user['city']
                 );

                if($id==0){
                    if($this->master_model->check_exist('users','email',$user['email'])){
                        $this->session->set_flashdata('msg', 'Email Address Already Exist');
                    }else{
                        if($this->db->insert('users',$data)){
                            $uid =$this->db->insert_id();
                            //Email Start
                            $fromName = $this->session->userdata('name');
                            $sub = "Your Account Details";
                            $msg = "Find below credentials of your account<br/>";
                            $msg.="<b>Email:</b>".$user['email']."<br><b>Password:</b>".$user['password'];
                            $msg.="<br/><br/>";
                            $msg.="<a href='".site_url()."/login'>Click here to login</a>";
                            $to = $user['email'];
                            $cc = '';
                            $this->master_model->mail($to,$cc,$sub,$msg,$fromName);

                            //Email End
                            $this->session->set_flashdata('msg', 'Successfully Created User');
                        }
                    }

                }else{
                    $this->db->where('uid',$id);
                    if($this->db->update('users',$data)){

                        $this->session->set_flashdata('msg', 'Successfully updated profile');
                    }
                }


         }



         if($id!=0){
         $user=$this->master_model->get_table('users','uid',$this->uri->segment(3));
         $data['user'] = $user[0];
         }

         $data['user_profile_edit'] =TRUE;

         $data['page']='admin/create_user';
	       $this->load->view('comman/template1',$data);
     }

     public function password($uid) {
          if($uid == UID){ //if session uid and current profileuid match only display data
             $this->load->helper(array('form'));


             if($this->input->post('user')){

                 //print_r($this->input->post('user'));

                 $userpost = $this->input->post('user');

                 $profile = $this->master_model->get_table('users','uid',$uid);
                 $profile = $profile[0];

                 if($profile['password'] == $userpost['oldpassword']){
                     $data = array('password'=>$userpost['password']);
                     $profile = $this->master_model->update_table('users',$data,'uid',$uid);
                    if($profile){
                        $this->session->set_flashdata('msg', 'Successfully updated your password');
                    }
                 }else{

                        $this->session->set_flashdata('msg', "Password didn't Matted Please try again");

                 }




             } //End Submit FOrm



             $data['page']='password-change';
             $this->load->view('comman/template1',$data);
         }else{
             show_404();
         }
     }

     public function photos(){
         $uid = UID;
         $data['photos'] = $this->master_model->get_user_photos($uid);

         $data['page']='photos';
	 $this->load->view('comman/template1',$data);
     }

     public function profile_likes() {
         $uid = UID;
         $profile = $this->master_model->get_userintrest($uid,1);

         $data['profile_likes'] = $profile;
         $data['type'] = "Liked";
         $data['page']='home/profile_likes';
	 $this->load->view('comman/template1',$data);
     }
     public function profile_views() {
         $uid = UID;
         $profile = $this->master_model->get_userintrest($uid,2);

         $data['profile_likes'] = $profile;
         $data['type'] = "Vist";
         $data['page']='home/profile_likes';
	 $this->load->view('comman/template1',$data);
     }

     public function logout(){
         $sessiondata = array(
                              'username' => '',
                              'loginuser' => false,
                              'uid'=>'',
                              'isAdmin'=>''
                         );
         $this->session->unset_userdata($sessiondata);
         $this->session->sess_destroy();
         redirect('login');
     }

     public function roadmap(){
       $sql = "SELECT r.id,r.project_id,p.name,r.module,r.time_line,r.created FROM road_map r LEFT JOIN projects p on r.project_id = p.pid";
      $data['roadmap']=$this->master_model->run_query($sql);
       $data['page']='roadmap';
       $this->load->view('comman/template1',$data);
     }
}

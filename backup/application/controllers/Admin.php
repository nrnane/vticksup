<?php

class Admin extends CI_Controller{
     public function __construct()
     {
          parent::__construct();


          if( trim($this->session->userdata('uid')) == ''){
                    redirect("login");
            }
             $this->load->model('master_model');
             if(USERTYPE!=0){
                 redirect("home");
             }

            $this->load->library("pagination");
            $this->load->library('grocery_CRUD');

     }

     public function _example_output($output = null)
     {
       $this->load->view('example.php',$output);
     }

     public function index()
     {


         $data['page']='admin/home';
	 $this->load->view('comman/template1',$data);

     }

     public function edit_tickets(){
       try{
   			$crud = new grocery_CRUD();

   			//$crud->set_theme('datatables');
   			$crud->set_table('tickets');
   			$crud->set_subject('Tickets');
        $crud->set_relation('project','projects','name');
        $crud->set_relation('status','tickets_s','status_name');
        $crud->set_relation('uid','users','name');
		
        $crud->display_as('id','TICKET ID');
        $crud->display_as('uid','User Name');
        $crud->unset_add();
        $crud->unset_delete();

        $crud->columns('id','isQuestion','project','title','description','uid','status');
        //$crud->field_type('isQuestion', 'select',array('0' => 'Ticket','1'=>'Question'));
        $crud->unset_fields('isQuestion','name','priority','attachments_id','time','date');
        $crud->unset_columns('isQuestion','name','priority','attachments_id','time','date');
   			//$crud->required_fields('city');
   			//$crud->columns('city','country','phone','addressLine1','postalCode');

   			$output = $crud->render();

   			$this->_example_output($output);

   		}catch(Exception $e){
   			show_error($e->getMessage().' --- '.$e->getTraceAsString());
   		}
     }

     public function all_users(){
          $config = array();





         if($this->session->userdata("superadmin")==1){
           $data['users']=$this->master_model->get_table('users');
           $data['page']='admin/all_users';
          $this->load->view('comman/template1',$data);
        }else{
          redirect("home/tickets");
        }


     }

     public function all_projects(){
          $config = array();




        $this->pagination->initialize($config);


        $data["projects"] = $this->master_model->get_table('projects');

         $data['page']='admin/all_projects';
	 $this->load->view('comman/template1',$data);
     }


      public function create_project($id) {

         if($this->input->post('project')){

                $project = $this->input->post('project');

                $data = array(
                     'name'=>$project['name']
                 );

                if($id==0){
                    if($this->master_model->check_exist('projects','name',$project['name'])){
                        $this->session->set_flashdata('msg', 'Project name already exist');
                    }else{
                        if($this->db->insert('projects',$data)){
                            //Email Start

                            //Email End
                            $this->session->set_flashdata('msg', 'Successfully created project');
                            redirect('admin/all_projects');
                        }
                    }

                }else{
                    $this->db->where('pid',$id);
                    if($this->db->update('projects',$data)){
                        $this->session->set_flashdata('msg', 'Successfully updated project');
                        redirect('admin/all_projects');
                    }
                }

           // print_r($data);

         }
         if($id!=0){
         $user=$this->master_model->get_table('projects','pid',$this->uri->segment(3));
         $data['project'] = $user[0];
         }

         $data['page']='admin/create_project';
	 $this->load->view('comman/template1',$data);
     }


     public function create_user($id) {
         $uid = $id;
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

                if(isset($user['roadmap_edit'])){
                  $roadmap = 2;
                }elseif (isset($user['roadmap_view'])) {
                  $roadmap = 1;
                }else{
                  $roadmap = 0;
                }

                $data = array(
                     'name'=>$user['name'],
                     'email'=>$user['email'],
                     'phone'=>$user['phone'],
                     'password'=>$password,
                     'address'=>$user['address'],
                     'city'=>$user['city'],
                     'usertype'=>$user['usertype'],
					 'superadmin'=>0,
                     'roadmap'=>$roadmap
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


            if($this->input->post('project_assing')==1){
            //print_r($_POST);
            $qq =  $this->db->query("DELETE FROM projects_assign  WHERE uid = $uid");

            if($this->input->post('project')){
             foreach($this->input->post('project') as $key=>$value){

                 $data = array(
                     'pid'=>$key,
                     'uid'=>$uid,
                     'assign'=>$value
                 );


                     $this->db->insert('projects_assign',$data);

             }
            }//End If Check Empty

         } //End Assign Users

           // print_r($data);

         }

         $projects = $this->master_model->get_table('projects');
         $data['projects'] = $projects;


         if($id!=0){
         $user=$this->master_model->get_table('users','uid',$this->uri->segment(3));
         $data['user'] = $user[0];
         }

         $data['page']='admin/create_user';
	 $this->load->view('comman/template1',$data);
     }

     public function delete_user($id) {
         $this->master_model->deleteUser($id);
         redirect('admin/all_users');
     }

     public function assign_project_to_users($uid) {

         if($uid!=0){
         $user=$this->master_model->get_table('users','uid',$this->uri->segment(3));
         $data['user'] = $user[0];
         }



         if($this->input->post('assign')){
            //print_r($_POST);
            $qq =  $this->db->query("UPDATE projects_assign SET assign=0 WHERE uid = $uid");
             foreach($this->input->post('project') as $key=>$value){

                 $data = array(
                     'pid'=>$key,
                     'uid'=>$uid,
                     'assign'=>$value
                 );

                 $this->db->where('pid',$key);
                 $q = $this->db->get('projects_assign');
                 if($q->num_rows()>0){
                     //------------
                     //--------------

                     $d = $q->row_array();
                     $this->db->where('id',$d['id']);
                     $this->db->update('projects_assign',$data);
                 }else{
                     $this->db->insert('projects_assign',$data);
                 }
             }

         }
         $projects = $this->master_model->get_table('projects');
         $data['projects'] = $projects;

         //$projects_assign =  $this->master_model->get_table('projects_assign','uid',$uid);
         //print_r($projects_assign);
         $data['page']='admin/assign_project_to_users';
	 $this->load->view('comman/template1',$data);
     }
     public function profile($uid) {
         //echo $uid.' _ '.UID;
         if($uid == UID){ //if session uid and current profileuid match only display data
             $this->load->helper(array('form'));


             if($this->input->post('user')){

                 //print_r($this->input->post('user'));

                $profile = $this->master_model->update_table('users',$this->input->post('user'),'uid',$uid);
                  if($profile){
                      $this->session->set_flashdata('msg', 'Successfully updated your profile');
                  }


             } //End Submit FOrm


             $profile = $this->master_model->get_table('users','uid',$uid);
             $data['profile'] = $profile[0];

             $data['page']='profile_edit';
             $this->load->view('comman/template1',$data);
         }else{
             show_404();
         }
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
}

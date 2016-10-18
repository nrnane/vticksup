<?php

class Login extends CI_Controller
{

     public function __construct()
     {
          parent::__construct();

          $this->load->helper('form');

          $this->load->database();
          $this->load->library('form_validation');
          //load the login model
          $this->load->model('login_model');
     }

     public function index()
     {
         if( trim($this->session->userdata('uid')) != ''){
			         redirect("home/tickets");
		}



          //get the posted values
          $username = $this->input->post("txt_username");
          $password = $this->input->post("txt_password");

          //set validations
          $this->form_validation->set_rules("txt_username", "Username", "trim|required");
          $this->form_validation->set_rules("txt_password", "Password", "trim|required");

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
              $data['page']='login_view';
              $this->load->view('comman/template1',$data);

          }
          else
          {
               //validation succeeds
               if ($this->input->post('btn_login') == "Login")
               {
                    //check if username and password is correct
                    $usr_result = $this->login_model->get_user($username, $password);
                    if ($usr_result->num_rows()>0) //active user record is present
                    {
                         //set the session variables
                        $user = $usr_result->row();
                         $sessiondata = array(
                              'name'=>$user->name,
                              'email'=>$user->email,
                              'loginuser' => TRUE,
                              'uid'=>$user->uid,
                              'usertype'=>$user->usertype,
                              'superadmin'=>$user->superadmin,
                              'roadmap'=>$user->roadmap
                         );
                         $this->session->set_userdata($sessiondata);
                         redirect("home/tickets");
                         //print_r($user);
                    }
                    else
                    {
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid username and password!</div>');
                         redirect('login/index');
                    }
               }
               else
               {
                    redirect('login/index');
               }
          }
     }
}
?>

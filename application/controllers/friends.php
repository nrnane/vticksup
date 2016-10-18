<?php

class friends extends CI_Controller{
     public function __construct()
     {
          parent::__construct();
          $this->load->model('master_model');
          
          if( trim($this->session->userdata('uid')) == ''){
			redirect("login");
		}
          
     }
     
     public function index()
     {
         //print_r($this->session->all_userdata()); 
                
        
         $uid = $this->session->userdata('uid');
         $data['friends'] = $this->master_model->get_friends($uid);
         $data['page']='friends';
	 $this->load->view('comman/template1',$data);
                
     }
     
     public function find(){
         $uid = $this->session->userdata('uid');
         $data['friends'] = $this->master_model->find_friends($uid);
         $data['page']='friends';
	 $this->load->view('comman/template1',$data);
     }
}

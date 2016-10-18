<?php

class Ajax_pagination extends CI_Controller{
     public function __construct()
     {
          parent::__construct();
          $this->load->model('post');
        $this->load->library('ajax_pagination');
        $this->perPage = 1;
        
          
     }
    
     
     public function index()
     {
         $data = array();
        
        //total rows count
        $totalRec = count($this->post->getRows());
        
        //pagination configuration
        $config['first_link']  = 'First';
        $config['div']         = 'postList'; //parent div tag id
        $config['base_url']    = base_url().'ajax_pagination/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $data['posts'] = $this->post->getRows(array('limit'=>$this->perPage));
        
        //load the view
        $this->load->view('posts/index', $data);
     }
     
     public function ajaxPaginationData()
    {
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //total rows count
        $totalRec = count($this->post->getRows());
        
        //pagination configuration
        $config['first_link']  = 'First';
        $config['div']         = 'postList'; //parent div tag id
        $config['base_url']    = base_url().'ajax_pagination/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $data['posts'] = $this->post->getRows(array('start'=>$offset,'limit'=>$this->perPage));
        
        //load the view
        $this->load->view('posts/ajax-pagination-data', $data, false);
    }
}
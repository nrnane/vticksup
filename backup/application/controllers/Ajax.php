<?php
require_once 'phpmail/PHPMailerAutoload.php';
require_once 'extra_library/ImageResize.php';
use \Eventviva\ImageResize;

class Ajax extends CI_Controller{
     public function __construct()
     {
          parent::__construct();
           if( trim($this->session->userdata('uid')) == ''){
                    redirect("login");
            }
          $this->load->model('master_model');
          $this->load->library("pagination");


     }

     public function index()
     {
         //print_r($this->session->all_userdata());

            //echo $val;
          if($this->uri->segment(2)){
             $username = $this->uri->segment(2);

             //$this->master_model->record_user_profile_visit();
            $data['userdata'] = $this->master_model->get_user_profile(0,$username);
            $data['page']='homepage';
            $this->load->view('comman/template1',$data);

          }

         //$uid = $this->session->userdata('uid');
         //$data['friends'] = $this->master_model->get_friends($uid);
         //$data['page']='friends';
	 //$this->load->view('comman/template1',$data);

     }

     public function ajax_tickets() {

        $method_name = $this->uri->segment(3);

         if($method_name==='tickets'){

               $type=0;
               $data['type_n']=0;
               $data['tbl_type']='tickets';
           }elseif($method_name=='questions'){

               $type=1;
               $data['type_n']=1;
               $data['tbl_type']='question';
           }


        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $config["per_page"] = 10;
        $data = $this->master_model->fetch_tickets($type,$config["per_page"], $page);
        $data["results"] = $data;

        $config = array();
        count($data);
        $config["base_url"] = site_url() . "/ajax/ajax_tickets/".$method_name;
        $config["total_rows"] = $totalRows = $this->master_model->fetch_tickets($type,'','',1); //1 for count
        $data['totalRows'] = $totalRows;
        $config["uri_segment"] = 4;
        //$config['use_page_numbers']  = TRUE;


        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();

         $data['count'] = $this->master_model->getTicketsCount($type);
         $data['type'] = $method_name;

	 $this->load->view('blocks/only_tickets',$data);
     }



     public function searchTickets() {

          $config = array();

         if($search = $this->input->post('search')){
            $data['searchData'] = $search;
             $this->session->set_userdata($search);
             }

            if($this->session->userdata('method_name')=='tickets'){
               $type=0;
               $data['type_n']=0;
               $data['tbl_type']='tickets';
           }elseif($this->session->userdata('method_name')=='questions'){
               $type=1;
               $data['type_n']=1;
               $data['tbl_type']='question';
           }

            $config["per_page"] = 10;
            $config["uri_segment"] = 4;
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //$datat = $this->master_model->fetch_tickets($this->session->userdata('type_n'),$config["per_page"],$page,'',$this->session->userdata('status'),$this->session->userdata('project'),$this->session->userdata('search'),$this->session->userdata('fromDate'),$this->session->userdata('toDate'));
            $data["totalRows"] =$this->master_model->fetch_tickets($this->session->userdata('type_n'),'','',1,$this->session->userdata('status'),$this->session->userdata('project'),$this->session->userdata('search'),$this->session->userdata('fromDate'),$this->session->userdata('toDate'));
            $config["total_rows"] = $data["totalRows"]; //1 for count

            $config["base_url"] = site_url() . "/ajax/searchTickets/".$this->session->userdata('method_name');
            $totalRows = $data["totalRows"];
            $this->pagination->initialize($config);



            $data["results"] =$this->master_model->fetch_tickets($this->session->userdata('type_n'),$config["per_page"],$page,'',$this->session->userdata('status'),$this->session->userdata('project'),$this->session->userdata('search'),$this->session->userdata('fromDate'),$this->session->userdata('toDate'));
            $data["links"] = $this->pagination->create_links();
            $data['type'] = $this->session->userdata('method_name');
            //$data['count'] =$totalRows;
            $data['count'] = $this->master_model->getTicketsCount($type);

             //$this->load->view('blocks/only_tickets',$data);

            $data['type'] = $this->session->userdata('method_name');
         $data['page']='homepage';
	 $this->load->view('comman/template1',$data);

     }



     public function ticket_status() {
         //print_r($_POST);
     }


     public function mail() {

         $femail = 'support@vaazu.com';
         $fname = 'Admin';
         $subject = 'Subject';
         $message = 'Hellow Testing';
         $to = 'nrnane@gmail.com';

        //$header = "From:webmaster@havafun.com \r\n";
        $header = "From:".$femail." \r\n";


        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

         $mail = new PHPMailer(); // create a new object
         $mail->IsSMTP(); // enable SMTP
         $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
         $mail->SMTPAuth = true; // authentication enabled
         //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
         $mail->Host = "mail.vaazu.com";
         $mail->Port = 25; // or 587
         $mail->IsHTML(true);
         $mail->Username = "support@vaazu.com";
         $mail->Password = "Member123";
         /*$mail->Username = "vaazuemail@gmail.com";
         $mail->Password = "Vaazu@2012";*/
         /*$mail->From = $femail;
         $mail->FromName = $fname;*/
         $mail->setFrom($femail,$fname);
         $mail->Subject = $subject;
         $mail->Body = $message;
         $mail->AddAddress($to);
         $mail->Send();
     }

     public function uploadFiles() {
         /* echo '<pre>';
         //print_r($_POST);
         print_r($_FILES);
         echo '</pre>';*/
         $img_ext = array("jpg", "jpeg", "png", "gif","bmp");

         $images_arr = array();
         echo '<ul class="reorder_ul reorder-photos-list">';
	for($i=0;$i<count($_FILES['images']['name']);$i++){
		$image_name = $_FILES['images']['name'][$i];
		$tmp_name 	= $_FILES['images']['tmp_name'][$i];
		$size 		= $_FILES['images']['size'][$i];
		$type 		= $_FILES['images']['type'][$i];
		$error 		= $_FILES['images']['error'][$i];

		############ Remove comments if you want to upload and stored images into the "uploads/" folder #############

                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
		$target_dir = realpath(base_url())."uploads/";
                //$newFileName = md5(date("Y-m-d H:i:s")).'.'.$ext;
                $explodeFIleName = explode('.',$image_name);
                $newFileName = str_replace(' ','',$explodeFIleName[0]).'_'.date("Y-m-d").'.'.$ext;
                $images_arr[] = $newFileName;
                $target_file = $target_dir.$newFileName;

                if(in_array($ext, $img_ext)){ //check if images
                 $image = new ImageResize($_FILES['images']['tmp_name'][$i]); //Resize and save in uploads fodler
                 $image->resizeToWidth(1000);
                    if($image->save($target_file)){
                            //Create Thumnail and save in small Folder
                            $image = new ImageResize($target_file);
                            $image->resizeToHeight(200);
                            $image->save($target_dir.'small/'.$newFileName);
                            echo '<li id="image_li_" class="ui-sortable-handle" style="width: 220px;word-break: break-word;">';
                            echo '<a href="javascript:void(0);" class="image_link" style="background-image:url(\''.base_url('uploads/small/'.$newFileName).'\')"></a>';
                            echo '<span class="filename">'.$newFileName.'</span>';
                    }
                    //IF Images
                }else{
                    move_uploaded_file($_FILES['images']['tmp_name'][$i],$target_file);
                    //IF Files
                    echo '<li id="doc_li_" class="ui-sortable-handle">';
                    echo '<a href="'.base_url('uploads/'.$newFileName).'" class="" target="_blank">'.base_url('uploads/'.$newFileName).'</a>';
                }
                 echo '<input type="hidden" name="attach[]" form="create_edit_form" value="'.$newFileName.'" />';
		echo '</li>';

	}//End Foreach

        echo '</ul>';
	//Generate images view

     }


}

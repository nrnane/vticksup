<?php
require_once 'phpmail/PHPMailerAutoload.php';
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class master_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();

        if (trim($this->session->userdata('uid')) != '') {
            define('UID', $this->session->userdata('uid'));
            define('USERTYPE', $this->session->userdata('usertype'));
            define('USERNAME', $this->session->userdata('name'));
            define('USEREMAIL', $this->session->userdata('email'));
        }
    }

    //get the username & password from tbl_usrs

    /*
     * Get User Profile by UId  Or Username
     */

    function get_user_profile($uid = '', $username = '') {
        $sql = "SELECT * FROM users WHERE ";

        if ($uid != '' || $uid != 0) {
            $sql.= "uid = $uid";
        }

        if ($username != '') {
            $sql.= "username LIKE '%" . $username . "%'";
        }

        //echo $sql;

        $query = $this->db->query($sql);
        $user = $query->row_array();



        return $user;
    }

    public function run_query($sql){
      $query = $this->db->query($sql);
      return  $query->result_array();
    }

    /*
     * Get User Instagram  Photos by Instagram UserId
     */

    public function get_table($table, $where1 = '', $wherevalue1 = '', $where2 = '', $wherevalue2 = '', $where3 = '', $wherevalue3 = '') {
        if ($where1 != '' && $wherevalue1 != '') {
            $this->db->where($where1, $wherevalue1);
        }

        if ($where2 != '' && $wherevalue2 != '') {
            $this->db->where($where2, $wherevalue2);
        }

        if ($where3 != '' && $wherevalue3 != '') {
            $this->db->where($where3, $wherevalue3);
        }

        $q = $this->db->get($table);
        if($q->num_rows()>0){
            return $q->result_array();
        }
    }

    public function update_table($table, $data, $where = '', $wherevalue = '') {
        if ($where != '' && $wherevalue != '') {
            $this->db->where($where, $wherevalue);
        }


        $q = $this->db->update($table, $data);
        if ($q) {
            return true;
        }
    }

    public function insert_table($table, $data) {

        $q = $this->db->insert($table, $data);
        if ($q) {
            return $this->db->insert_id();
        }
    }

    public function check_exist($table, $where, $wherevalue) {
        $this->db->where($where, $wherevalue);
        $q = $this->db->get($table);
        if ($q->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function record_count() {
        if (USERTYPE == 1) {

            $this->db->where('uid', UID);
        }

        $q = $this->db->where('isQuestion', 0)->get('tickets');

        $d = $q->num_rows();
        return $d;
    }

    public function count($table,$where='',$wherevalue='') {

        if (USERTYPE == 1) {
            $this->db->where('uid', UID);
        }

         if ($where != '' && $wherevalue != '') {
            $this->db->where($where,$wherevalue);
         }

        $q = $this->db->get($table);

        $d = $q->num_rows();
        return $d;
    }

    public function fetch_tickets($isQuestion='0',$limit, $start,$count='',$by_status='',$by_project='',$search_word='',$from_date='',$to_date='') {
        $sql = "
                SELECT
            p.name as project,

          t.id,t.isQuestion,t.title,t.description,t.status,t.attachments_id,ts.status_id,ts.status_name,ts.class_name,t.uid,t.time,t.date,u.uid,u.name,u.email,
          (SELECT time FROM tickets_status WHERE tid = t.id ORDER by id DESC LIMIT 1)  as last_status_time,
          (SELECT count(*) FROM tickets_status WHERE tid = t.id)  as reply_count,
           (SELECT count(*) FROM attachments WHERE group_id = t.attachments_id) as attach_count
           FROM tickets t
         LEFT JOIN users  as u on t.uid = u.uid
         LEFT JOIN projects as p on t.project = p.pid
         LEFT JOIN ";
        if($isQuestion==0){
            $sql.="tickets_s";
        }else{
            $sql.="question_s";
        }
        $sql.=" as ts on  t.status = ts.status_id";
        /*  if(isset($r['id'])){
          $tid = $r['id'];
          $where = $r['where'];
          $sql.=" WHERE ".$where." = ".$tid." AND";
          }else if($usertype == 1){
          $sql.=" WHERE t.uid =".$uid." AND";
          }else {
          $sql.=" WHERE";
          } */


        if (USERTYPE == 1) {
            $sql.=" WHERE  t.uid =" . UID . " AND ";
        }elseif(USERTYPE==2 || USERTYPE==0){


        if($by_project=='' || $by_project==100){ // if project filter
            $sql.=" WHERE ";

            $q = $this->get_table('projects_assign','uid',UID);
            //print_r($q);
            $i = 1;
            $qcount = count($q);
              $sql.= " (";
            foreach($q as $p){
                $sql.=" project =" . $p['pid'];
                    if($i!=$qcount){
                        $sql.=" OR ";
                    }
                $i+=1;
            }
            $sql.= ") ";
            $sql.= " AND ";


            }else{ //End if project filter
                $sql.=" WHERE";
            }

        } else {
            $sql.=" WHERE";
        }

        if($by_project!='' && $by_project!=100){
            $sql.=" project =" . $by_project." AND ";
        }

        if($from_date!='' && $to_date!=''){

            //$sql.=" t.date BETWEEN '$from_date' AND '$to_date' AND ";
            $sql.=" t.date BETWEEN '$from_date' AND DATE_ADD('$to_date',INTERVAL 1 DAY) AND ";
        }

        $sql.=" t.isQuestion = $isQuestion";

        if($by_status!='' && $by_status!=100){
            $sql.=" AND t.status = $by_status";
        }

        if($search_word!=''){
            $sql.=" AND
 t.id LIKE '%$search_word%' OR
 project LIKE '%$search_word%' OR
 t.title LIKE '%$search_word%' OR
 t.description LIKE '%$search_word%' OR
 ts.status_name LIKE '%$search_word%' OR
 u.name LIKE '%$search_word%' OR
 u.email LIKE '%$search_word%' ";
        }

        $sql.=" ORDER by t.id DESC ";

        /*if($count=='' && $by_status==''){
        $sql.=" LIMIT $start,$limit";
        }*/

        if($start!='' || $limit!=''){

        $sql.=" LIMIT $start,$limit";
        }

        //echo $sql;

        //echo '<div class="alert alert-info">'.$sql.'</div>';
        //$this->db->limit($limit, $start);
        $query = $this->db->query($sql);
        if($count==''){
            if ($query->num_rows() > 0) {
                return $query->result();
            }else{
                return false;
            }
        }else{
            return $query->num_rows();
        }

    }

    public function fetch_table($table, $limit, $start) {
        $sql = "SELECT * FROM $table";
        $sql.=" LIMIT $start,$limit";


        //$this->db->limit($limit, $start);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getalltickets($where = '', $wherevalue = '',$isQuestion=0) {

        //$usertype = $this->session->user_data('');

        $sql = "
                SELECT
                p.pid,
            p.name as project,

          t.id,t.isQuestion,t.title,t.description,t.status,t.attachments_id,ts.status_id,ts.status_name,ts.class_name,t.uid,t.time,t.date,u.uid,u.name,u.email,
          (SELECT time FROM tickets_status WHERE tid = t.id ORDER by id DESC LIMIT 1)  as last_status_time,
          (SELECT count(*) FROM tickets_status WHERE tid = t.id)  as reply_count,
           (SELECT count(*) FROM attachments WHERE group_id = t.attachments_id) as attach_count
           FROM tickets t
         LEFT JOIN users  as u on t.uid = u.uid
         LEFT JOIN projects as p on t.project = p.pid
         LEFT JOIN ";
         if($isQuestion==0){
            $sql.="tickets_s";
        }else{
            $sql.="question_s";
        }
        $sql.=" as ts on  t.status = ts.status_id
                 ";
        if ($where != '' && $wherevalue != '') {

            $sql.=" WHERE " . $where . " = " . $wherevalue . " AND";
        } else if (USERTYPE == 1) {
            $sql.=" WHERE t.uid =" . UID . " AND";
        } else {
            $sql.=" WHERE";
        }

        $sql.=" t.isQuestion = $isQuestion";
        $sql.=" ORDER by t.id DESC ";

        $q = $this->db->query($sql);
        $d = $q->result();



        return $d;
    }

    public function ticketStatus($where = '', $wherevalue = '',$isQuestion=0) {

        $sql = "
                SELECT
            u.uid,u.name,u.email,ts.id,ts.tid as ticket_id,ts.uid,ts.status,ts.attachments_id,ts.reopen,ts.message,ts.time, tstatus.status_name, tstatus.class_name,
            (SELECT count(*) FROM attachments WHERE group_id = ts.attachments_id) as attach_count
            FROM
            tickets_status ts
            LEFT JOIN users as u on ts.uid = u.uid
            LEFT JOIN ";
         if($isQuestion==0){
            $sql.="tickets_s";
        }else{
            $sql.="question_s";
        }
        $sql.=" as tstatus on ts.status = tstatus.status_id ";
        if ($where != '' && $wherevalue != '') {
            $sql.=" WHERE " . $where . " = " . $wherevalue;
        }
        $sql.=" ORDER by ts.id ASC";

        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            return $q->result();
        }
    }

    public function get_projects_list() {


             $q = $this->get_table('projects_assign','uid',UID);
             if(!empty($q)){
            foreach($q as $p){
                $d = $this->get_table('projects', 'pid', $p['pid']);
                $result[] = $d[0];
            }
            return $result;
             }

    }


    public function get_project_assigned_users($pid,$usertype='') {

            $sql = "SELECT p.id,p.uid,p.pid,p.assign,p.date,u.name,u.email,u.usertype FROM projects_assign p
LEFT JOIN users u on p.uid = u.uid
 WHERE pid = $pid";
            if($usertype!=''){
                $sql.=" AND u.usertype = $usertype ";
            }else{
                $sql.=" AND u.usertype != 1";
            }
            $q = $this->db->query($sql);

            if($q->num_rows()>0){
                return $q->result_array();
            }

    }

    public function time_ago($session_time) {
        $time_difference = time() - $session_time;

        $seconds = $time_difference;
        $minutes = round($time_difference / 60);
        $hours = round($time_difference / 3600);
        $days = round($time_difference / 86400);
        $weeks = round($time_difference / 604800);
        $months = round($time_difference / 2419200);
        $years = round($time_difference / 29030400);
        // Seconds
        if ($seconds <= 60) {
            return "$seconds seconds ago";
        }
        //Minutes
        else if ($minutes <= 60) {

            if ($minutes == 1) {
                return "1 minute ago";
            } else {
                return "$minutes minutes ago";
            }
        }
        //Hours
        else if ($hours <= 24) {

            if ($hours == 1) {
                return "1 hour ago";
            } else {
                return "$hours hours ago";
            }
        }
        //Days
        else if ($days <= 7) {

            if ($days == 1) {
                return "1 day ago";
            } else {
                return "$days days ago";
            }
        }
        //Weeks
        else if ($weeks <= 4) {

            if ($weeks == 1) {
                return "1 week ago";
            } else {
                return "$weeks weeks ago";
            }
        }
        //Months
        else if ($months <= 12) {

            if ($months == 1) {
                return "1 month ago";
            } else {
                return "$months months ago";
            }
        }
        //Years
        else {

            if ($years == 1) {
                return "1 year ago";
            } else {
                return "$years years ago";
            }
        }
    }

//End Timestamp//

    public function time_to_date($param) {
        return date('Y-m-d H:i:s', $param);
    }

    public function getTicketsCount($type = 0) { //0 for rickets, 1 for questions
        $uid = UID;
        $sql = "SELECT s.status_id,s.status_name,s.class_name,(SELECT count(*) FROM tickets t WHERE t.status = s.status_id and t.isQuestion = $type";
        if (USERTYPE == 1) {
            $sql.=" and t.uid = $uid";
        }

        if (USERTYPE == 2 || USERTYPE == 0) {
            //$sql.=" and t.uid = $uid";
            $sql.= " and ";
            $q = $this->get_table('projects_assign','uid',$uid);
            //print_r($q);
            $i = 1;
            $qcount = count($q);
              $sql.= " (";
            foreach($q as $p){
                $sql.=" project =" . $p['pid'];
                    if($i!=$qcount){
                        $sql.=" OR ";
                    }
                $i+=1;
            }
            $sql.= ") ";

        }

        if ($type == 0) {
            $sql.=") as count FROM tickets_s s ORDER by s.id ASC";
        } else {
            $sql.=") as count FROM question_s s ORDER by s.id ASC";
        } //End Type

        $q = $this->db->query($sql);
        if ($q->num_rows()) {
            return $q->result_array();
        }
    }

    public function question_status_update() {
        if ($ticket = $this->input->post('ticket')) {
            $tid = $this->uri->segment(3);
            $ticket_id = $ticket['id'];
            $project_id = $ticket['pid'];
            print_r($ticket);
            $getTicketUser = $this->master_model->get_table('users','uid',$ticket['uid']);
            $getTicketUser = $getTicketUser[0];

            if($this->input->post('reply')){
                    if($ticket['status']==0){
                        // $ticket_status = 1;
                         //$touser = "Support Team"
                         if (USERTYPE==0 || USERTYPE==2) { //Admin or Support Team
                           $ticket_status = 1;
                           if($getTicketUser['usertype']==1){
                             $touser = "Manager";
                           }elseif ($getTicketUser['usertype']==0) {
                             $touser = "Admin";
                           }

                           $tomails = 5;
                         }elseif (USERTYPE==1) { // Manager
                           $ticket_status = 0;
                           $touser = "Support Team";
                           $tomails = 6;
                         }


                     }elseif($ticket['status']==1 || $ticket['status']==2){
                       if (USERTYPE==0 || USERTYPE==2) { //Admin or Support Team
                         $ticket_status = $ticket['status'];
                             if($getTicketUser['usertype']==1){
                               $touser = "Manager";
                             }elseif ($getTicketUser['usertype']==0) {
                               $touser = "Admin";
                             }
                         $tomails = 5;
                       }elseif (USERTYPE==1) { // Manager
                         $ticket_status = 0;
                         $touser = "Support Team";
                         $tomails = 6;
                       }
                     }
                      $btn_press_type = "Replied";

                      $flash_msg = "Question replied successfully";
            }//End Reply


            if($this->input->post('completed')){

                if($getTicketUser['usertype']==1){
                    $touser = "Manager";
                  }elseif ($getTicketUser['usertype']==0) {
                    $touser = "Support Team";
                  }
                  $ticket_status = 2;
                  $tomails = 6;

                $btn_press_type = "Completed";
                $flash_msg = "Question completed successfully";
            }//End completed

             //
            $tdata = array('status' => $ticket_status);
            $this->db->where('id', $ticket['id']);
            $this->db->update('tickets', $tdata);


            if ($attach = $this->input->post('attach')) {
                $attach_id = UID . '_' . time();
                foreach ($attach as $key => $value) {

                    $adata = array(
                        'img_name' => $value,
                        'uid' => UID,
                        'group_id' => $attach_id
                    );
                    $this->master_model->insert_table('attachments', $adata);
                }
            } else {
                $attach_id = '';
            }

            $time = time();
            $fdata = array(
                'tid' => $ticket['id'],
                'uid' => UID,
                'status' => $ticket_status,
                'message' => $ticket['message'],
                'attachments_id' => $attach_id,
                'time' => $time
            );

            if ($this->insert_table('tickets_status', $fdata)) {


                $this->sendmail($getTicketUser['email'], $tomails, $touser, USERNAME, USEREMAIL, "Question-" . $ticket['id'] . " " . $btn_press_type . " - " . $ticket['title'] . ", " . USERNAME, "<b>Question Id:</b>Question-" . $ticket['id'] . "<br/><br/><b>Question Title:</b>" . $ticket['title'], "<b>Question " . $btn_press_type . " Message:</b>" . $ticket['message'], "<b>Status:</b>" . $this->get_column('question_s', 'status_id', $ticket_status, 'status_name'), site_url("home/view_question/" . $ticket['id']),$project_id);

                $this->session->set_flashdata('msg', $flash_msg);
                redirect('home/view_question/' . $tid);
            }




        }//End Post
    }

    public function ticket_status_update() {
        if ($this->input->post('ticket')) {
            $tid = $this->uri->segment(3);

            //print_r($_POST);
            $ticket = $this->input->post('ticket');
            $ticket_id = $ticket['id'];
            $ticket_uid = $ticket['uid'];
            $message = $ticket['message'];
            $project_id = $ticket['pid'];

            $ticket_created_user_type = $this->get_column('users', 'uid', $ticket_uid,'usertype');

            $this->db->where('uid', $ticket['uid']); // Get Ticket User Data
            $q = $this->db->get('users');
            $ticket_user = $q->row_array();

            if ($this->input->post('approve')=='Approve') { //if approve
                $ticket_status = 1;
                $btn_press_type = "Approved";
                 $touser = "Support Team";
                $tomails = 2;
                $flash_msg = "Ticket Approved Successfully";
            }
			
			 if ($this->input->post('reopen')=='Re-Open') { //if approve
                $ticket_status = 1;
                $btn_press_type = "Re-Opened";
                $touser = "Support Team";
                $tomails = 2;
                $flash_msg = "Ticket Re-Opened Successfully";
            }

            if ($this->input->post('reply')) {

                if ($ticket['status'] == 0) {
                    if (USERTYPE == 0 || USERTYPE == 2) { //Admin or Support Team
                        $ticket_status = 4;
                        $touser = "Manager";
                        $tomails = 3;
                    } elseif (USERTYPE == 1) { // Manager
                        $ticket_status = 0;
                        $touser = "Admin";
                        $tomails = 4;
                    }
                } elseif ($ticket['status'] == 1) {

                    if (USERTYPE == 0 || USERTYPE == 2) { //Admin or Support Team
                        if ($ticket_user['usertype'] == 0 && USERTYPE == 0) { // if admin replay his ticket
                            $ticket_status = 1;
                            $touser = "Support Team";
                            $tomails = 6;
                        } else {

                            $ticket_status = 5;
                            $touser = "Manager";
                            $tomails = 5;
                        }
                    }elseif (USERTYPE==1) { // Manager
                        $ticket_status = 1;
                        $touser = "Support Team";
                        $tomails = 6;
                      }

                } elseif ($ticket['status'] == 4) {
                    if (USERTYPE == 0 || USERTYPE == 2) { //Admin or Support Team
                        $ticket_status = 4;
                        $touser = "Manager";
                        $tomails = 7;
                    } elseif (USERTYPE == 1) { // Manager
                        $ticket_status = 0;
                        $touser = "Admin";
                        $tomails = 4;
                    }
                } elseif ($ticket['status'] == 5) {
                    if (USERTYPE == 0 || USERTYPE == 2) { //Admin or Support Team
                        if($ticket_created_user_type == USERTYPE && $ticket_uid == UID){
                            $ticket_status = 1;
                        }else{
                            $ticket_status = 5;
                        }
                        
                        $touser = "Manager";
                        $tomails = 7;
                    } elseif (USERTYPE == 1) { // Manager
                        $ticket_status = 1;
                        $touser = "Support Team";
                        $tomails = 6;
                    }
                } elseif ($ticket['status'] == 6) {
                    if (USERTYPE == 2) { //Admin or Support Team
                        $ticket_status = 6;

                        if($ticket_user['usertype']==0){
                            $touser = "Admin";
                        }else{
                            $touser = "Manager";
                        }

                        $tomails = 7;
                    } elseif (USERTYPE == 0 || USERTYPE == 1) { //Admin or Manager
                        $ticket_status = 1;
                        $touser = "Support Team";
                        $tomails = 6;
                    }
                }

                $flash_msg = "Ticket Replied Successfully";
                $btn_press_type = "Replied";
            }//end reply

            if ($this->input->post('completed')) {
                $ticket_status = 6;
                if (USERTYPE == 0) {
                    $touser = "Manager";
                } elseif (USERTYPE == 2) {
                    if ($ticket_user['usertype']==0) {
                        $touser = "Admin";
                    } else {
                        $touser = "Manager";
                    }
                } //end if
                $flash_msg = "Ticket Completed Successfully";
                $btn_press_type = "Completed";
                $tomails = 7;
            }

            if ($this->input->post('closeticket')) {
                $ticket_status = 3;

                if (USERTYPE == 0) {
                    $touser = "Manager";
                } elseif (USERTYPE == 1) {
                    if ($ticket['status'] == 0 || $ticket['status'] == 4) {
                        $touser = "Admin";
                    } else {
                        $touser = "Support Team";
                    }
                } //end if

                $flash_msg = "Ticket Closed Successfully";
                $btn_press_type = "Closed";
                 $tomails = 6;
            }




            //Update Ticket Status in Tickets Table
            $tdata = array('status' => $ticket_status);
            $this->db->where('id', $ticket['id']);
            $this->db->update('tickets', $tdata);


            if ($attach = $this->input->post('attach')) {
                $attach_id = UID . '_' . time();
                foreach ($attach as $key => $value) {

                    $adata = array(
                        'img_name' => $value,
                        'uid' => UID,
                        'group_id' => $attach_id
                    );
                    $this->master_model->insert_table('attachments', $adata);
                }
            } else {
                $attach_id = '';
            }
            $time = time();
            $fdata = array(
                'tid' => $ticket['id'],
                'uid' => UID,
                'status' => $ticket_status,
                'message' => $ticket['message'],
                'attachments_id' => $attach_id,
				'reopen'=>0,
                'time' => $time
            );
			if ($this->input->post('reopen')=='Re-Open') {
                $fdata['reopen']=1;
             }
			 
            if ($this->master_model->insert_table('tickets_status', $fdata)) {


                $this->master_model->sendmail($ticket_user['email'], $tomails, $touser, USERNAME, USEREMAIL, "Ticket-" . $ticket_id . " " . $btn_press_type . " - " . $ticket['title'] . ", " . USERNAME, "<b>Ticket Id:</b>Ticket-" . $ticket_id . "<br/><br/><b>Ticket Title:</b>" . $ticket['title'], "<b>Ticket " . $btn_press_type . " Message:</b>" . $message, "<b>Status:</b>" . $this->get_column('tickets_s', 'status_id', $ticket_status, 'status_name'), site_url("home/view_ticket/" . $ticket_id),$project_id);

                $this->session->set_flashdata('msg', $flash_msg);
                redirect('home/view_ticket/' . $tid);
            }
        }//if Post
    }

    public function mail_meesage_format($fname, $touname, $title, $msg, $status, $link) {
        $message = "Dear " . $touname . ",<br><br><br>";
        $message.=$title . "<br><br>";
        $message.=$msg . "<br><br>";
        $message.=$status . "<br><br>";
        $message.="<br>Click <a href='" . $link . "'>here</a> to view details.";
        $message.="<br><br><br><br>Thanks<br><b>" . $fname . "</b>";
        return $message;
    }

    public function single_mail($to,$cc,$sub,$msg,$fromName) {
        $mail = new PHPMailer(); // create a new object
         $mail->IsSMTP(); // enable SMTP
         $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
         $mail->SMTPAuth = true; // authentication enabled
         //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
         $mail->Host = "mail.vaazu.com";
         $mail->Port = 25; // or 587
         $mail->IsHTML(true);
         $mail->Username = "support@vaazu.com";
         $mail->Password = "Member123";

         $mail->setFrom(USEREMAIL, $fromName);
         $mail->AddCC($cc);

        $mail->Subject =$sub;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->Send();
    }

    public function sendmail($touser, $tomails = 0, $touname, $fname, $femail, $sub, $title, $msg, $status, $link,$project_id, $isSingle = 0) {



         $mail = new PHPMailer(); // create a new object
         $mail->IsSMTP(); // enable SMTP
         $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
         $mail->SMTPAuth = true; // authentication enabled
         //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
         $mail->Host = "";
         $mail->Port = 25; // or 587
         $mail->IsHTML(true);
         $mail->Username = "";
         $mail->Password = "";



        $mail->setFrom($femail, $fname);
        $to = $touser;

        $cc = '';

        //$subject = $subject." , by ".$fromname." - Email:".$fromemail;
        $subject = $sub;
        $message = "Dear " . $touname . ",<br><br><br>";
        $message.=$title . "<br><br>";
        $message.=$msg . "<br><br>";
        $message.=$status . "<br><br>";
        $message.="<br>Click <a href='" . $link . "'>here</a> to view details.";
        $message.="<br><br><br><br>Thanks<br><b>" . $fname . "</b>";

        $mail->AddCC($cc);

        $mail->Subject =$sub;
        $mail->Body = $message;


        if ($isSingle == 0) {

            switch ($tomails) {
                case 0:
                    $mail->AddAddress($to);
                    break;


                case 1: //1 CreteTicekt - Send to admin cc all support and created user
                    $AllToUsers = $this->get_project_assigned_users($project_id,0);
                    //$cc.="Cc:";
                    foreach ($AllToUsers as $au) {
                        $too = $au['email'];
                        $mail->AddAddress($too);
                    }

                    $AllCCUsers = $this->get_project_assigned_users($project_id,2);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }
                    $mail->AddCC($femail);

                    break;



                case 2: //2 ticketApproved - admin approve ticket send mails to support team in this we can take ticket user email and send as cc


                    $AllCCUsers = $this->get_project_assigned_users($project_id,2);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddAddress($too);
                    }
                    $mail->AddCC($femail);


                    $AllToUsers = $this->get_project_assigned_users($project_id,0);
                    //$cc.="Cc:";
                    foreach ($AllToUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }

                    $mail->AddCC($to);



                    break;



                case 3: //3 ticketReplay  - admin sending reply to manager - in this we can take ticket user email and send as to waiting for approve (waiting for inistiator)

                    $mail->AddAddress($to);

                    $AllCCUsers = $this->get_project_assigned_users($project_id);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }

                    break;
                case 4: //ticketReplay - manager sending reply to admin - in this we cant take ticket user email as cc when ticket status 4



                    $AllCCUsers = $this->get_project_assigned_users($project_id,0);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddAddress($too);
                    }

                    $mail->AddCC($to);

                    $AllCCUsers = $this->get_project_assigned_users($project_id,2);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }

                    break;



                case 5: //5 ticketReplay - support team sending mail to manager reply status we can take ticekt user email as to
                    $mail->AddAddress($to);
                    $AllCCUsers = $this->get_project_assigned_users($project_id);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }
                    break;

                case 6: //6 ticketReplay in this manager sending to support team, we can take ticekt user email as cc
                    $AllCCUsers = $this->get_project_assigned_users($project_id,2);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddAddress($too);
                    }

                    $mail->AddCC($to);

                    $AllCCUsers = $this->get_project_assigned_users($project_id,0);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }
                    break;

                case 7: //7 ticketReplay in this admin or support team sending mail to manager ticket user take it as to
                    $mail->AddAddress($to);

                    $AllCCUsers = $this->get_project_assigned_users($project_id);
                    //$cc.="Cc:";
                    foreach ($AllCCUsers as $au) {
                        $too = $au['email'];
                        $mail->AddCC($too);
                    }
                    break;
                default:
                    $mail->AddAddress($to);
            } //switch end
        } else {
            $mail->AddAddress($to);
        }


       // $mail->Send();
    }

    public function get_assign_($param){

    }

    public function getRecords($sql) {
        $r = $this->db->query($sql);
        if($r->num_rows()>0){
            return $d = $r->result_array();
        }
    }

    public function mail($to, $cc, $sub, $message, $fromName = '', $fromEmail = 'support@vaazu.com') {
      $mail = new PHPMailer(); // create a new object
      $mail->IsSMTP(); // enable SMTP
      $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
      $mail->SMTPAuth = true; // authentication enabled
      //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
      $mail->Host = "mail.vaazu.com";
      $mail->Port = 25; // or 587
      $mail->IsHTML(true);
      $mail->Username = "support@vaazu.com";
      $mail->Password = "Member123";



     $mail->setFrom($fromEmail, $fromName);

     $mail->Subject =$sub;
     $mail->Body = $message;

     $mail->AddAddress($to);
     $mail->Send();

    }

    public function get_column($table, $where, $wherevalue, $coloumn) {
        $this->db->select($coloumn);
        $this->db->where($where, $wherevalue);

        $q = $this->db->get($table);
        $d = $q->row_array();
        return $d[$coloumn];
    }

    public function deleteUser($uid) {
        if(USERTYPE==0){
            $this->db->query("DELETE FROM users WHERE uid =".$uid);
            $this->db->query("DELETE FROM tickets WHERE uid =".$uid);
            $this->db->query("DELETE FROM tickets_status WHERE uid =".$uid);
            $this->db->query("DELETE FROM projects_assign WHERE uid =".$uid);
            $this->db->query("DELETE FROM attachments WHERE uid =".$uid);
        }
    }



}

?>

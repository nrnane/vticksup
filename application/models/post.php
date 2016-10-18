<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Post extends CI_Model{
    
    function __construct() {
        $this->postTable = 'tickets';
    }
    
    function getRows($params = array())
    {
        $this->db->select('*');
        $this->db->from($this->postTable);
        $this->db->order_by('id','desc');
        
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        
        $query = $this->db->get();
        
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
}
?>
<?php

class Users_model extends CI_Model{

    // Function to get all user's name to send push notificaton.
    public function getUserDetailsByToken($token =''){
        $this->db->select('id');
        $this->db->select('first_name');
        $this->db->from('users');
        if($token !='')
            $this->db->where('store_token', $token);
        $result = $this->db->get();
        return $result->result_array();
    }
	
    // Function to fetch all active discount coupon's in database.
    public function getAllActiveCoupons(){
        $this->db->from('coupon_codes');
        $this->db->where("Status","Active");
        $result = $this->db->get();
        return $result->result_array();
    }
    
    // Function to get all user's details by ids.
    public function getUserDetailsByID($ids){
        $this->db->from('users');
        if($ids !='')
            $this->db->where_in('id', $ids);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    //Function to add notification record in table.
    public function addNotificationRecord($noti_data){
        $this->db->insert('notification_records', $noti_data);
        return $this->db->insert_id();
    }

   // Function to get server key of particular store.
    public function get_store_server_key($token){ 
	$this->db->select('server_key');
        $this->db->from('store_server_key');
        $this->db->where('token', $token);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    // Function to get user details by id.
    public function getUserDetailsByUserID($ids){
        $this->db->from('users');
        $this->db->where('id', $ids);
        $result = $this->db->get();
        return $result->result_array();
    }
  
}

?>

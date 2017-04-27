<?php

class Activity_model extends CI_Model{

	public function get_user_activity_of_store_by_token($token, $lst_wk){
		$this->db->select('uh.id, uh.user_id, uh.history_type, uh.history_data, uh.create_date');
		$this->db->from('user_history uh');
		$this->db->join('users u');
	}
}
?>
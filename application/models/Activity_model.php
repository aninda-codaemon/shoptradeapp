<?php

class Activity_model extends CI_Model{

	public function get_total_user_activity_of_store_by_token($token, $lst_wk='', $start_date='', $end_date=''){
		$this->db->select('uh.id, uh.user_id, uh.history_type, uh.history_data, uh.create_date, u.first_name, u.last_name, u.email');
		$this->db->from('user_history uh');
		$this->db->join('users u', 'u.id = uh.user_id');
		$this->db->where('uh.store_id', $token);

		if (!empty($lst_wk)){
			$this->db->where('create_date >=', $lst_wk);
		}else{
			$this->db->where('DATE_FORMAT(create_date, "%Y-%m-%d") >=', $start_date);
			$this->db->where('DATE_FORMAT(create_date, "%Y-%m-%d") <=', $end_date);	
		}

		return $this->db->count_all_results();
	}

	public function get_user_activity_of_store_by_token($token, $lst_wk, $offset, $limit){
		$this->db->select('uh.id, uh.user_id, uh.history_type, uh.history_data, uh.create_date, u.first_name, u.last_name, u.email');
		$this->db->from('user_history uh');
		$this->db->join('users u', 'u.id = uh.user_id');
		$this->db->where('uh.store_id', $token);
		$this->db->where('create_date >=', $lst_wk);
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	public function get_user_activity_of_store_by_token_search($token, $start_date, $end_date, $offset, $limit){
		$this->db->select('uh.id, uh.user_id, uh.history_type, uh.history_data, uh.create_date, u.first_name, u.last_name, u.email');
		$this->db->from('user_history uh');
		$this->db->join('users u', 'u.id = uh.user_id');
		$this->db->where('uh.store_id', $token);
		$this->db->where('DATE_FORMAT(create_date, "%Y-%m-%d") >=', $start_date);
		$this->db->where('DATE_FORMAT(create_date, "%Y-%m-%d") <=', $end_date);
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	public function get_activity_details_by_id($id){
		$this->db->select('uh.id, uh.user_id, uh.history_type, uh.history_data, uh.create_date, u.first_name, u.last_name, u.email');
		$this->db->from('user_history uh');
		$this->db->join('users u', 'u.id = uh.user_id');
		$this->db->where('uh.id', $id);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	
}
?>
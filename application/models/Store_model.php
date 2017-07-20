<?php

class Store_model extends CI_Model{

	public function check_store_exist_by_domain($shop_domain){
		$this->db->from('store');
		$this->db->where('domain', $shop_domain);
		$this->db->or_where('myshopify_domain', $shop_domain);
		return $this->db->count_all_results();
	}
	
	public function get_store_info_by_domain($shop_domain){
		$this->db->select('id, token as store_id, store_name, shop_owner, domain as shop, customer as email, key as access_token, app_status,install_status');
		$this->db->from('store');
		$this->db->where('domain', $shop_domain);
		$this->db->or_where('myshopify_domain', $shop_domain);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function save_store_info($shop_info){
		$data = array(
					'token' => $shop_info['id'],
					'type' => 'ShopifyWrapper',
					'store_name' => $shop_info['name'],
					'customer' => $shop_info['email'],
					'domain' => $shop_info['domain'],
					'shop_owner' => $shop_info['shop_owner'],
					'myshopify_domain' => $shop_info['myshopify_domain'],
					'iana_timezone' => $shop_info['iana_timezone'],
					'key' => $shop_info['tokens']['access_token'],
                    'install_status' => 'app_installed',
					'data_dump' => serialize(json_encode($shop_info))
				);

		$this->db->insert('store', $data);
		return $this->db->insert_id();
	}

	public function update_store_info($domain, $data=array()){
		$this->db->where('domain', $domain);
		$this->db->update('store', $data);
		return 1;
	}
	
	public function update_store_entry_by_domain($shop_domain, $status){		
		//$this->db->delete('store', array('domain' => $shop_domain));
		$this->db->update('store', array('app_status', $status), array('domain' => $shop_domain));
		
		if (empty($this->db->error())){
			return 1;
		}else{
			return 0;
		}
	}

	public function check_if_store_payment_exist($params=array()){

		$this->db->from('store_payment');
		$this->db->where('store_token', $params['store_token']);
		return $this->db->count_all_results();
	}

	public function save_store_payment_info($shop_info=array()){
		$data = array(
					'store_token' => $shop_info['store_token'],					
					'charge_id' => $shop_info['id'],					
					'charge_value' => floatval($shop_info['price']),
					'charge_status' => $shop_info['status'],
					'charge_description' => serialize(json_encode($shop_info)),					
				);

		$this->db->insert('store_payment', $data);
		return $this->db->insert_id();
	}

	public function update_store_payment_info($shop_info=array()){
		$data = array(									
					'charge_id' => $shop_info['id'],					
					'charge_value' => floatval($shop_info['price']),
					'charge_status' => $shop_info['status'],
					'charge_description' => serialize(json_encode($shop_info)),					
				);

		$this->db->set('update_date', 'NOW()', false);
		$this->db->update('store_payment', $data, ['store_token' => $shop_info['store_token']]);

		if (empty($this->db->error())){
			return 1;
		}else{
			return 0;
		}	
	}

	public function check_if_active_payment_exist_for_store(){
		
	}
        
}

?>

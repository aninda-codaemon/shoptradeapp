<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/shopify.php');

class Notification extends CI_Controller {
       
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('users_model','users'); 
        $this->load->model('store_model', 'store');
    }
    

    public function index()
    {	
		
    }

    public function sendNotification()
    { 
        $coupon_details = $this->users->getAllActiveCoupons();
        //get store info
        $shop               = $this->session->userdata('shop');
        $store_details  = $this->store->get_store_info_by_domain($shop);
        
	//Get all users details from database
	//$user_details = $this->users->getUserDetailsByToken('7894561230');
	$user_details = $this->users->getUserDetailsByToken($store_details['store_id']);
	
	$data = array();
        foreach($user_details as $key => $val)
        {	
            $result['id'] = $val['id'];
            $result['userName'] = $val['first_name'];
            $data[] = $result;
        }
        
        
        // On submit set rule and check if validate.
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('lstOffers', 'Offers', 'trim|required');
        //$this->form_validation->set_rules('lstStores', 'Stores', 'trim|required');
        $this->form_validation->set_rules('lstUsers', 'Users', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('lstCouponCode', 'Coupon Code', 'trim|required');
        //$this->form_validation->set_rules('image', 'Image', 'trim|required');
            
        if (!($this->form_validation->run() == FALSE)) {//form validated 
            $res = array(); 
           $res['lstOffers'] = $this->input->post('lstOffers');
           $res['message'] = $this->input->post('message');
           $res['description'] = $this->input->post('description');
           $res['lstCouponCode'] = $this->input->post('lstCouponCode');
           $imageName = time() . "_" . $_FILES['image']['name'];
		
           $ids = explode(",",$this->input->post("userids")); 
           //file upload code
            if($_FILES){
                $config['upload_path'] = 'uploads'; 
                $config['file_name'] = time() . "_" . $_FILES['image']['name'];
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'jpg|jpeg|png|gif';
                $this->load->library('upload', $config);
                $this->upload->do_upload('image');
                $this->upload->initialize($config);
                $this->upload->set_allowed_types('*');
                $this->upload->do_upload('image');
            }
            $user_detail = $this->users->getUserDetailsByID($ids);
            
          
            if($res['lstOffers'] == '1') 
            { 
                foreach($user_detail as $key => $val)
                {	
                    //Insert notification record into notification_records table
                    $noti_data = array(
                    'userID'=> $val['id'],
                    'message'=> $res['message'],
                    'description'=> $res['description'],
                    'images'=> $imageName,
                    'created_date'=> date('Y-m-d'),
                    );
                    $noti_record = $this->users->addNotificationRecord($noti_data);
		
		    // Fetch store server key.
		    $serverKey = $this->users->get_store_server_key($store_details['store_id']);
		    //$serverKey = $this->users->get_store_server_key('7894561230');
		    if($serverKey != '')
		    	$server_key = $serverKey[0]['server_key'];
			//echo '<pre>'; print_R($server_key); exit;
                    $this->curlCall($server_key,$val,$res,$imageName);

                }
            }
        }
                
        $this->load->view('layout/notification_view', array('userInfo' => $data,'coupon_details' => $coupon_details,'store_details'=>$store_details));
     }

    public function curlCall($server_key,$val,$res,$imageName)
    { 
        //echo "<pre>"; print_r($val['gcm_reg_token']); 
        $url = 'https://fcm.googleapis.com/fcm/send';
		    
        $target = $val['gcm_reg_token'];  
        $fields = array();
        if(is_array($target)){
                 $fields = array(
             'registration_ids' =>$target,         
              'data' =>array (
                            "body" => $res['message'],
                            "title" => $res['description'],
                            "icon" => base_url().'/public/images/'.$imageName,
                            "coupon_code"=>$res['lstCouponCode']
                    ),
            'notification' => array (
                            "body" => $res['message'],
                            "title" => $res['description'],
                            "icon" => base_url().'/public/images/'.$imageName
                            
                    )

            );
        }else{
                $fields = array (
                    'to' => $target,
                    'notification' => array (
                            "body" => $res['message'],
                            "title" => $res['description'],
                            "icon" => base_url().'/public/images/'.$imageName
                            
                    ),
                    'data' =>array (
                            "body" => $res['message'],
                            "title" => $res['description'],
                            "icon" => base_url().'/public/images/'.$imageName,
                            "coupon_code"=>$res['lstCouponCode']
                    )
            );
        } 
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
            }

            curl_close($ch);
            //echo $result;
	    
    }
    
    public function getUserByTokenID()
    {
        $token = $this->input->post("token");
        $data = array();
        $user_details = $this->users->getAllUserDetails($token);
        foreach($user_details as $key => $val){
            $result['id'] = $val['id'];
            $result['userName'] = $val['first_name'];
            $data[] = $result;
        }
        echo json_encode($data);
    }
    
    //function to send push notification to particluar user id
    public function user_push_notification($user_id)
    {   
        //Get the user details
        $user_detail = $this->users->getUserDetailsByUserID($user_id);
        //Get all active coupons
        $coupon_details = $this->users->getAllActiveCoupons();
        //print_R($coupon_details); exit;
                
        //get store info
        $shop               = $this->session->userdata('shop');
        $store_details  = $this->store->get_store_info_by_domain($shop);
        
        // On submit set rule and check if validate.
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('lstCouponCode', 'Coupon Code', 'trim|required');
                    
        if (!($this->form_validation->run() == FALSE)) {//form validated 
           $res = array(); 
           $user_id = $this->input->post('user_id');
           $res['message'] = $this->input->post('message');
           $res['description'] = $this->input->post('description');
           $res['lstCouponCode'] = $this->input->post('lstCouponCode');
           $imageName = '';
           
           foreach($user_detail as $key => $val)
            {	
                //Insert notification record into notification_records table
                $noti_data = array(
                'userID'=> $val['id'],
                'message'=> $res['message'],
                'description'=> $res['description'],
                'images'=> $imageName,
                'created_date'=> date('Y-m-d'),
                );
                $noti_record = $this->users->addNotificationRecord($noti_data);

                // Fetch store server key.
                $serverKey = $this->users->get_store_server_key($store_details['store_id']);
                //$serverKey = $this->users->get_store_server_key('7894561230');
                if($serverKey != '')
                    $server_key = $serverKey[0]['server_key'];
                    //echo '<pre>'; print_R($server_key); exit;
                $this->curlCall($server_key,$val,$res,$imageName);

            }
           
        }
        $this->load->view('layout/user_notification_view', array('user_details'=>$user_detail[0], 'coupon_codes'=>$coupon_details));
    }

}

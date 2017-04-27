<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/shopify.php');

class Notification extends CI_Controller {
       
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('users_model','users');        
    }
    
    /**
    * Function to check the merchant    
    * preference    
    **/
    
	/**
	 * get object instance.
	 *
	 * @return void
	 */
	
//	public function __construct()
//	{
//		 $store = DB::table('store')
//			->where('token',1234567890)
//			->first();
//		$factory = new FactoryClass();
//		$this->interfaceobj = $factory->getInstance($store->type);
//		$this->interfaceobj->setStore($store);
//	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
		
    }

    public function getAllUsers()
    { 
        //Get all users details from database
	$user_details = $this->users->getAllUserDetails();
        $data = array();
        foreach($user_details as $key => $val)
        {	
            $result['id'] = $val['id'];
            $result['userName'] = $val['first_name'];
            $data[] = $result;
        }
        $coupon_details = $this->users->getAllActiveCoupons();
        
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
           $imageName = time() . "_" . $_FILES['image']['name'];;
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
            $user_details = $this->users->getUserDetailsByID($ids);
            //echo '<pre>'; print_r($user_details); exit;
          
            if($res['lstOffers'] == '1') //
            {
                foreach($user_details as $key => $val)
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

                    if($val['store_token'] == '5458254512')
                    {
                        $server_key = 'AIzaSyD-WdGvAqztwUf3q6Kbll-8u3gV_DL9poQ';
                        $this->curlCall($server_key,$val,$res,$imageName);

                    }  
                    else if($val['store_token'] == '7894561230')
                    { 
                        $server_key = 'AIzaSyCmEm_us9TAJXJr_FfAXOH5l0dMMGUQbLc';
                        $this->curlCall($server_key,$val,$res,$imageName);
                    }   

                }
            }
        }
                
        $this->load->view('layout/notification_view', array('userInfo' => $data,'coupon_details' => $coupon_details));
     }
     
//     public function sendNotification()
//     {      //echo '<pre>'; print_R($_FILES['image']); exit;
//           $res = array();
//           $res['lstOffers'] = $this->input->post('lstOffers');
//           $res['message'] = $this->input->post('message');
//           $res['description'] = $this->input->post('description');
//           $res['lstCouponCode'] = $this->input->post('lstCouponCode');
//           $imageName = time() . "_" . $_FILES['image']['name'];;
//           $ids = explode(",",$this->input->post("userids")); 
//           //Image Upload Code
//            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
//            $this->form_validation->set_rules('lstOffers', 'Offers', 'trim|required');
//            $this->form_validation->set_rules('lstStores', 'Stores', 'trim|required');
//            $this->form_validation->set_rules('lstUsers', 'Users', 'trim|required');
//            $this->form_validation->set_rules('message', 'Message', 'trim|required');
//            $this->form_validation->set_rules('description', 'Description', 'trim|required');
//            $this->form_validation->set_rules('lstCouponCode', 'Coupon Code', 'trim|required');
//            //$this->form_validation->set_rules('image', 'Image', 'trim|required');
//            
//        if (!($this->form_validation->run() == FALSE)) {//form validated 
//            if($_FILES){
//                //echo 'image'; exit;
//            $config['upload_path'] = 'uploads'; 
//            $config['file_name'] = time() . "_" . $_FILES['image']['name'];
//            $config['overwrite'] = TRUE;
//            $config["allowed_types"] = 'jpg|jpeg|png|gif';
////            $config["max_size"] = 1024;
////            $config["max_width"] = 400;
////            $config["max_height"] = 400;
//            //echo '<pre>'; print_r($config); exit;
//            $this->load->library('upload', $config);
//            $this->upload->do_upload('image');
//            $this->upload->initialize($config);
//            $this->upload->set_allowed_types('*');
//            $this->upload->do_upload('image');
//            
//            }
//            $user_details = $this->users->getUserDetailsByID($ids);
//            //echo '<pre>'; print_r($user_details); exit;
//          
//            if($res['lstOffers'] == '1') //
//            {
//                foreach($user_details as $key => $val)
//                {	
//                    //Insert notification record into notification_records table
//                    $noti_data = array(
//                    'userID'=> $val['id'],
//                    'message'=> $res['message'],
//                    'description'=> $res['description'],
//                    'images'=> $imageName,
//                    'created_date'=> date('Y-m-d'),
//                    );
//                    $noti_record = $this->users->addNotificationRecord($noti_data);
//
//                    if($val['store_token'] == '5458254512')
//                    {
//                        $server_key = 'AIzaSyD-WdGvAqztwUf3q6Kbll-8u3gV_DL9poQ';
//                        $this->curlCall($server_key,$val,$res,$imageName);
//
//                    }  
//                    else if($val['store_token'] == '7894561230')
//                    { 
//                        $server_key = 'AIzaSyCmEm_us9TAJXJr_FfAXOH5l0dMMGUQbLc';
//                        $this->curlCall($server_key,$val,$res,$imageName);
//                    }   
//
//                }
//            }
//        }
//        
//            
//        }
    
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
//            else
//            {
//                redirect('notification/getAllUsers');
//            }
            curl_close($ch);
            //return $result;
	    
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
    
}

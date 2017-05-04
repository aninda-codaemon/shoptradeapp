<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/shopify.php');

class Shopify extends CI_Controller {
    public $secret;
    public $shop;
    public $scope;
    public $api_key;
    public $_per_page = 10;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('store_model', 'store');
        $this->load->model('activity_model', 'activity');
    }
    
    /**
    * Function to check the merchant    
    * preference    
    **/
    public function preference() {
        $this->_data['api_key'] = $_SESSION['api_key'];
        $this->_data['shop'] = $_SESSION['shop'];

        $this->load->view('layout/preference', $this->_data);
    }
            
    /**
    * Landing page for the merchant user always
    * comes here for the autehntication
    * after redirecting from shopify app
    **/
    public function welcome_app() {
        session_unset();

        $shop           = $this->input->get('shop');
        $scope          = $this->input->get('scope');
        $api_key        = $this->input->get('api');
        $secret         = $this->input->get('secret');
        
        ////Load shopify API related files                
        $shopifyClient  = new ShopifyClient($shop, "", $api_key, $secret);
        
        // Now, request the token and store it in your session.
        // Url to redirect and get the app authorise key/access token
        $auth_url       = base_url() . $this->config->item('shopify_auth_url');
        
        ///// redirect to authorize url /////        
        redirect($shopifyClient->getAuthorizeUrl($scope, $auth_url, ' '), 'location');        
        exit;        
    }
    
    /**
    * Redirection page for the merchant
    * Generate access tokens for the shopify
    * api calls
    **/
    public function get_shopify_merchant_info(){
                    
        $code               = $this->input->get('code');
        $hmac               = $this->input->get('hmac');

        $api_key            = $this->config->item('shopify_api_key');
        $api_secret         = $this->config->item('shopify_api_secret');
        $shop               = $this->input->get('shop');

        $this->session->set_userdata('shop', $shop);

        //check if store is already registered or not
        $store_exist        = $this->store->check_store_exist_by_domain($shop);

        //save the shop info into the table
        ////Load shopify API related files        
        ///// call shopify API to get access(offline) token /////
        $shopifyClient      = new ShopifyClient($shop, "", $api_key, $api_secret);
        $access_data        = $shopifyClient->getAccessToken($code);

        /*echo '<pre>';
        print_r($_GET);
        print_r($this->config->item('shopify_api_key'). ' ' . $this->config->item('shopify_api_key'));
        print_r($shopifyClient);
        print_r($access_data);
        die();*/

        if ($store_exist > 0){
            
            //update the access token
            $dataArray          = array('key' => $access_data['access_token']);
            $this->store->update_store_info($shop, $dataArray);

            //get the shop token access data
            $response           = $this->store->get_store_info_by_domain($shop);

        }else{
            
            //Shopify client call to fetch merchant info
            $sc_shop            = new ShopifyClient($shop, $access_data['access_token'], $api_key, $api_secret);

            // Get shop info            
            $shop_info          = $sc_shop->call('GET', '/admin/shop.json');
            $shop_info['tokens']= $access_data;
            
            //save shop info
            $result             = $this->store->save_store_info($shop_info);

            $response           = array(
                                        'shop' => $shop_info['domain'],
                                        'store_id' => $shop_info['id'],
                                        'access_token' => $access_data['access_token'],
                                        'email' => $shop_info['email'],
                                        'shop_owner' => $shop_info['shop_owner']
                                    );
            
            ///Redirect to the app dashboard for first time users///    
            $redirect_url = "https://".$shop."/admin/apps/shoptrade-app";
            redirect($redirect_url, 'location');
            die();
        }        
                    
        $this->user_activity();
    }

    /**
    * Function to display all the user
    * activities for last 7 days
    **/
    public function user_activity(){
        //echo '<pre>Shop: '.$this->session->userdata('shop');
        //get the shop token access data
        $page               = 1;
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        //print_r($response);

        //get all the user activity for the store
        $last_wk            = date('Y-m-d', strtotime('-30 days'));
        $start_date         = '';
        $end_date           = '';

        //get total records        
        $total_activity     = $this->activity->get_total_user_activity_of_store_by_token($response['store_id'], $last_wk, $start_date, $end_date);        
        $per_page           = $this->_per_page;
        $total_page         = floor($total_activity/$per_page);
        $current_page       = ($page - 1) * $per_page;
        $next_page          = $page + 1;

        $user_activity      = $this->activity->get_user_activity_of_store_by_token($response['store_id'], $last_wk, $current_page, $per_page);

        //print_r($user_activity);
        
        $this->load->view('layout/user_activity', array('user_activity' => $user_activity, 'last_wk' => $last_wk, 'from_date' => $start_date, 'to_date' => $end_date, 'total_page' => $total_page, 'current_page' => $page, 'next_page' => $next_page));
    }
    
    /**
    * Function to display the activity
    * details for a user activity
    * Method: GET
    * Params:
    * $activity_id : Activity ID of 
    * user activity
    **/
    public function activity_details($activity_id){
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        $api_key            = $this->config->item('shopify_api_key');
        $api_secret         = $this->config->item('shopify_api_secret');
        
        //get activity details
        $activity_details   = $this->activity->get_activity_details_by_id($activity_id);

        //echo '<pre>';
        //print_r($response);
        //print_r(json_decode(unserialize($activity_details['history_data'])));
        
        $product_data       = json_decode(unserialize($activity_details['history_data']));
        $product_details    = array();
        $product_ids        = '';
        
        if (is_array($product_data)){
            
            foreach ($product_data as $value) {

                if (!empty($product_ids)){
                    $product_ids .= ',';    
                }

                $product_ids .= $value;                
            }

        }else{            
            $product_ids    = $product_data;
        }
        
        //Shopify client call to fetch product info
        $sc_shop            = new ShopifyClient($shop, $response['access_token'], $api_key, $api_secret);
        
        $product_info       = $sc_shop->call('GET', '/admin/products.json?ids='.$product_ids);      
        
        $this->load->view('layout/user_activity_details', array('activity_details' => $activity_details, 'product_info' => $product_info));
    }

    /**
    * Function to display all the user
    * activities by searching through
    * a set of date range
    * Method: POST
    **/
    public function search(){

        $page               = 1;
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        $from_date          = $this->input->post('from_date', true);
        $to_date            = $this->input->post('to_date', true);

        $last_wk            = '';
        $start_date         = date('Y-m-d', strtotime($from_date));
        $end_date           = date('Y-m-d', strtotime($to_date));

        if ($start_date > $end_date){
            $tmp = $end_date;
            $start_date = $end_date;
            $end_date = $start_date;
        }

        //get total records        
        $total_activity     = $this->activity->get_total_user_activity_of_store_by_token($response['store_id'], $last_wk, $start_date, $end_date);        
        $per_page           = $this->_per_page;
        $total_page         = floor($total_activity/$per_page);
        $current_page       = ($page-1)*$per_page;
        $next_page          = $page + 1;
        
        $search_result      = $this->activity->get_user_activity_of_store_by_token_search($response['store_id'], $start_date, $end_date, $current_page, $per_page);

        $this->load->view('layout/user_activity', array('user_activity' => $search_result, 'last_wk' => $last_wk, 'from_date' => $from_date, 'to_date' => $to_date, 'total_page' => $total_page, 'current_page' => $page, 'next_page' => $next_page, 'app_title' => 'Search User Activity For A Date Range'));
    }

    /**
    * Function to get the pages for
    * 
    * Method: POST
    **/
    public function ajax_get_activity_page(){
        $page               = $this->input->post('page', true);;
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        $from_date          = $this->input->post('from_date', true);
        $to_date            = $this->input->post('to_date', true);

        $last_wk            = $this->input->post('last_wk', true);

        if (empty($last_wk)){
            $start_date     = date('Y-m-d', strtotime($from_date));
            $end_date       = date('Y-m-d', strtotime($to_date));

            if ($start_date > $end_date){
                $tmp        = $end_date;
                $start_date = $end_date;
                $end_date   = $start_date;
            }
        }else{
            $start_date     = '';
            $end_date       = '';
        }
        
        //get total records        
        $total_activity     = $this->activity->get_total_user_activity_of_store_by_token($response['store_id'], $last_wk, $start_date, $end_date);        
        $per_page           = $this->_per_page;
        $total_page         = floor($total_activity/$per_page);
        $current_page       = ($page-1)*$per_page;
        $next_page          = $page + 1;        
        
        if (empty($last_wk)){
            $search_result  = $this->activity->get_user_activity_of_store_by_token_search($response['store_id'], $start_date, $end_date, $current_page, $per_page);
        }else{
            $search_result  = $this->activity->get_user_activity_of_store_by_token($response['store_id'], $last_wk, $current_page, $per_page);
        }

        $page_data          = $this->load->view('layout/ajax_activity_table_data', array('user_activity' => $search_result, 'last_wk' => $last_wk, 'from_date' => $from_date, 'to_date' => $to_date, 'total_page' => $total_page, 'current_page' => $page, 'next_page' => $next_page), true);   

        $response           = array(
                                    'total_activity' => $total_activity,
                                    'total_page' => $total_page,
                                    'current_page' => $page,
                                    'next_page' => $next_page,
                                    'page_data' => $page_data
                                );

        echo json_encode($response);
    }
    
    /**
    * Function to call the curl
    * for the api
    * $url : string
    * $method : string
    * $data : array
    **/
    private function _call_api($url, $method, $data = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if($method == 'POST')
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        //curl_setopt($curl, CURLOPT_POSTFIELDS, $product);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close ($curl);

        return $response;
    }
}

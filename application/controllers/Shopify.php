<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/shopify.php');

class Shopify extends CI_Controller {
    public $secret;
    public $shop;
    public $scope;
    public $api_key;

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
        
        //Shopify client call to fetch merchant info
        $sc_shop            = new ShopifyClient($response['shop'], $response['access_token'], $api_key, $api_secret);

        // Get shop info
        //$orders             = $sc_shop->call('GET', '/admin/orders.json?status=any');        
        /*echo '<pre>';
        print_r($orders);
        die();*/
            
        $this->user_activity();
    }

    /**
    * Function to display all the user
    * activities for last 7 days
    **/
    public function user_activity(){
        //echo '<pre>Shop: '.$this->session->userdata('shop');
        //get the shop token access data
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        //print_r($response);

        //get all the user activity for the store
        $last_wk            = date('Y-m-d', strtotime('-7 days'));

        $user_activity      = $this->activity->get_user_activity_of_store_by_token($response['store_id'], $last_wk);

        //print_r($user_activity);
        
        $this->load->view('layout/user_activity', array('user_activity' => $user_activity));
    }
    
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
        $shop               = $this->session->userdata('shop');
        $response           = $this->store->get_store_info_by_domain($shop);
        $from_date          = $this->input->post('from_date', true);
        $to_date            = $this->input->post('to_date', true);

        $start_date         = date('Y-m-d', strtotime($from_date));
        $end_date           = date('Y-m-d', strtotime($to_date));

        if ($start_date > $end_date){
            $tmp = $end_date;
            $start_date = $end_date;
            $end_date = $start_date;
        }

        $search_result      = $this->activity->get_user_activity_of_store_by_token_search($response['store_id'], $start_date, $end_date);

        $this->load->view('layout/user_activity', array('user_activity' => $search_result, 'from_date' => $from_date, 'to_date' => $to_date));
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

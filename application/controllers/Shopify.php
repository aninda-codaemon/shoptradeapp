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
        //$this->load->model('user_model', 'user');
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
        ////Use session library
        ////Unset the seesion data
        session_unset();

        $shop = $this->input->get('shop');
        $scope = $this->input->get('scope');
        $api_key = $this->input->get('api');
        $secret = $this->input->get('secret');

        ////Load shopify API related files                
        $shopifyClient = new ShopifyClient($shop, "", $api_key, $secret);

        // Now, request the token and store it in your session.
        // Url to redirect and get the app authorise key/access token
        $auth_url = base_url() . $this->config->item('shopify_auth_url');

        //echo $shopifyClient->getAuthorizeUrl($scope, $auth_url); die();

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
        echo '<pre>';
        echo 'In merchant info';
        print_r($this->input->get());
        $code               = $this->input->get('code');
        $hmac               = $this->input->get('hmac');

        $api_key            = $this->config->item('shopify_api_key');
        $api_secret         = $this->config->item('shopify_api_secret');
        $shop               = $this->input->get('shop');

        //$_SESSION['api_key'] = $this->input->get('api_key');
        //$_SESSION['secret'] = $this->input->get('secret');
        
        //check if store is already registered or not
        $store_exist        = $this->store->check_store_exist_by_domain($shop);

        if ($store_exist > 0){
            echo 'exist';

            //get the shop token access data
            $response           = $this->store->get_store_info_by_domain($shop);

        }else{
            echo 'not exist';

            //save the shop info into the table
            ////Load shopify API related files        
            ///// call shopify API to get access(offline) token /////
            $shopifyClient      = new ShopifyClient($shop, "", $api_key, $api_secret);
            $access_data        = $shopifyClient->getAccessToken($code);

            //Shopify client call to fetch merchant info
            $sc_shop            = new ShopifyClient($shop, $access_data['access_token'], $api_key, $api_secret);

            // Get shop info
            //$orders                 = $sc_shop->call('GET', '/admin/orders.json?status=any');
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
            print_r($shopifyClient);
            print_r($access_data);

            $redirect_url = "https://".$shop."/admin/apps/shoptrade-app-test";            
            redirect($redirect_url, 'location');
            die();
        }

        print_r($response);

        //Shopify client call to fetch merchant info
        $sc_shop            = new ShopifyClient($response['shop'], $response['access_token'], $api_key, $api_secret);

        // Get shop info
        $orders             = $sc_shop->call('GET', '/admin/orders.json?status=any');        
        echo '<br>';
        print_r($orders);
        
        die();
        
        $this->load->view('layout/order_listing', $orders);
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

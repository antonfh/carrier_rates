<?php	
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Network\Request;

class ShopifyAPIController extends AppController
{

    private  $redirect_uri;
    private  $shop;
    private  $api_key;
    private  $scope;
    private  $shared_secret;
    private  $token;
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ShopifyCurl');
        $this->loadComponent('ShopifyCarrierAPI');
        $this->redirect_uri = Configure::read('CTRACK.APP_URI');
        $this->shop = Configure::read('CTRACK.MY_SHOP');
        $this->api_key = Configure::read('CTRACK.API_KEY');
        $this->scope = Configure::read('CTRACK.SCOPE');
        $this->shared_secret = Configure::read('CTRACK.APP_SHARED_SECRET');
    }

    /**
    * Main route function when the App needs to be installed and redirects to 
    * the shop page - where confirmation and access is requested to install the app
    *
    * Step 1 End node - links to Shop and request to link app with store
    * TODO: Add checks and other fields as per Shopify docs
    */
	public function index()
    {
        $this->response->type('json');
        $this->autoRender = false;
       
        try {
            if ($this->request->is('get') && 
                isset($this->request->query['shop'])) {
                
                $install_url = "https://" . $this->shop . 
                                ".myshopify.com/admin/oauth/authorize?client_id=" . $this->api_key . 
                                "&scope=" . $this->scope . 
                                "&redirect_uri=" . urlencode($this->redirect_uri);

                $this->redirect($install_url);
            }
        }
        catch(Exception $e) 
        {
            return $e->message;
        }
    }

    /**
    * Function to request access to  install the App and to get a Token then
    * 
    *  STEP 2:
    *  CALL The Shop back - Shopify to return an Access Token with this Call
    */
    public function activate() {
        //Setup Cake to not return a template and use json (as from what I understand)
        $this->response->type('json');
        $this->autoRender = false;

        //Get some variables pre-calls and from rquests
        $code = $this->request->query['code'];
        $hmac = $this->request->query['hmac'];
        $timestamp = $this->request->query['timestamp'];
        $signature = $this->request->query['signature'];
        $signature_data = $this->shared_secret . 
                            "code=" . $code . 
                            "hmac=" . $hmac . 
                            "shop=". $this->shop . 
                            ".myshopify.comtimestamp=" . $timestamp;

        /*
        * Validation of Authenticity - Only valid call if Signature and hmac hashes matches
        */
        if (md5($signature_data) == $signature) {

            /*
            * Build the payload to POST to Shopify service endpoint : 
            *  /admin/oauth/access_token
            *  https://docs.shopify.com/api/authentication/oauth
            */ 
            $query = array(
                "Content-type" => "application/json",
                "client_id" => $this->api_key,
                "client_secret" => $this->shared_secret,
                "code" => $code
            );
      
            //Use the Shopfy Curl component at /Component/ShopifyCurlComponent to send the request to Server
            $shopify_response = $this->ShopifyCurl->shopify_call(
                    NULL, 
                    $this->shop, 
                    "/admin/oauth/access_token", 
                    $query, 
                    'POST'
                );

            $shopify_response = json_decode($shopify_response['response'], TRUE);
            $this->token = $shopify_response['access_token'];

            //Ask the Shopify Carrier API to save our token to the Db    
            $this->ShopifyCarrierAPI->setToken($this->shop, $this->token);  


            //Enable the App now since we have the Token 
            $response = $this->enableAppOnShopify();
        }
        
    }

    /**
    * Private function used after valid token return to enable the application on Shopify
    * 
    * https://docs.shopify.com/api/carrierservice#create
    * 
    */
    private function enableAppOnShopify()
    {
        $this->response->type('json');
        $this->autoRender = false;

        $query = array(
                    "carrier_service" => array(
                        "Content-type" => "application/json",
                        "name" => "CarrierRates",
                        "callback_url" => "http:\/\/carrier2.anton.co.za\/carrier\/rates",
                        "format" => "json",
                        "service_discovery" => true
                    )
                );
      
            //Use the Shopfy Curl component at /Component/ShopifyCurlComponent to send the request to Server
            $shopify_response = $this->ShopifyCurl->shopify_call($this->token, $this->shop, "/admin/carrier_services", $query, 'POST');
       
            $shopify_response = json_decode($shopify_response['response'], TRUE);

             return $shopify_response;
    }           


}
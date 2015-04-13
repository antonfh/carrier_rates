<?php	
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Network\Request;

/**
 * Class ShopifyAPIController
 *
 * Shopify API base class to handle setting up the CarrierRates app on the uAfrica4
 * demo test shop. Enable app on store, gets token and activates app on store with token
 *
 * @author Anton Heuschen <antonfh@gmail.com>
 * @package App\Controller
 */
class ShopifyAPIController extends AppController
{
    private  $_redirect_uri;
    private  $_shop;
    private  $_api_key;
    private  $_scope;
    private  $_shared_secret;
    private  $_token;

	/**
	 * Initialize the class
	 *
	 * Initialize the ShopifyAPI Controllers private methods:
	 *  -redirect_url,
	 *  _shop,
	 *  _api_key,
	 *  _scope,
	 *  _shared_secret
	 */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ShopifyCurl');
        $this->loadComponent('ShopifyCarrierAPI');

        $this->_redirect_uri = Configure::read('CTRACK.APP_URI');
        $this->_shop = Configure::read('CTRACK.MY_SHOP');
        $this->_api_key = Configure::read('CTRACK.API_KEY');
        $this->_scope = Configure::read('CTRACK.SCOPE');
        $this->_shared_secret = Configure::read('CTRACK.APP_SHARED_SECRET');
    }

    /**
     * Main route function when the App needs to be installed and redirects to
     * the shop page - where confirmation and access is requested to install the app
     *
     * Step 1 End node - links to Shop and request to link app with store
     * TODO: Add checks and other fields as per Shopify docs
     *
     * @return void
    */
	public function index()
    {
        $this->response->type('json');
        $this->autoRender = false;

	    //Yes it is only a redirect - testing how Cake acts on various error / exception
        try {
            if ($this->request->is('get') && 
                isset($this->request->query['shop'])) {
                
                $install_url = "https://" . $this->_shop .
                                ".myshopify.com/admin/oauth/authorize?client_id=" . $this->_api_key .
                                "&scope=" . $this->_scope .
                                "&redirect_uri=" . urlencode($this->_redirect_uri);

                $this->redirect($install_url);
            }
        }
        catch(Exception $e) 
        {
            return $e->getMessage();
        }
    }

    /**
    * Function to request access to  install the App and to get a Token then
    * 
    *  STEP 2:
    *  CALL The Shop back - Shopify to return an Access Token with this Call
     *
     * TODO: Test Some Cake Exception Handling
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

        $signature_data = $this->_shared_secret .
                            "code=" . $code . 
                            "hmac=" . $hmac . 
                            "shop=". $this->_shop .
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
                "client_id" => $this->_api_key,
                "client_secret" => $this->_shared_secret,
                "code" => $code
            );

            //Use the Shopfy Curl component at /Component/ShopifyCurlComponent to send the request to Server
            $shopify_response = $this->ShopifyCurl->shopify_call(
                    NULL, 
                    $this->_shop,
                    "/admin/oauth/access_token", 
                    $query, 
                    'POST'
                );

            //Seems sometimes an array return not json or no response?
            if (isset($shopify_response['response'])) {

                $shopify_response_token = json_decode($shopify_response['response'], TRUE);
	            if (json_last_error() === JSON_ERROR_NONE){
		            $this->_token = $shopify_response_token['access_token'];

		            //Ask the Shopify Carrier API to save our token to the Db
		            $this->ShopifyCarrierAPI->setToken($this->_shop, $this->_token);

		            //Enable the App now since we have the Token
		            $response = $this->_enableAppOnShopify();

		            $result = array('Status' => 200,
			            'Response' => $response,
			            'Result' => 'Success');

	            } else {
		            $result = array('Status' => 404,
			            'Response' => 'Could not find token' . $shopify_response,
			            'Result' => 'Failure');
                }
            }
            else {
	            $result = array('Status' => 500,
		            'Response' => 'No response from Shopify' . $shopify_response,
		            'Result' => 'Failure');
            }
        }
	    else {
		    $result = array('Status' => 403,
			    'Response' => 'Shopify key and secret does not match - possible spoof',
			    'Result' => 'Failure');
	    }

	    return $result;
    }

    /**
    * Private function used after valid token return to enable the application on Shopify
    * 
    * https://docs.shopify.com/api/carrierservice#create
    * 
    */
    private function _enableAppOnShopify()
    {
        $this->response->type('json');
        $this->autoRender = false;

        $sfpayload = array( "name" => "CarrierRates",
                            "callback_url" => "http://carrier2.anton.co.za/carrier/rates",
                            "format" => "json",
                            "service_discovery" => "true"
                );

        $query  = json_encode(array("carrier_service" => $sfpayload ));
      
        //Use the Shopfy Curl component at /Component/ShopifyCurlComponent to send the request to Server
        $shopify_response = $this->ShopifyCurl->shopify_call(
                                                    $this->_token,
                                                    $this->_shop,
                                                    '/admin/carrier_services', 
                                                    $query, 
                                                    'POST'
                                                );
        $result = json_decode($shopify_response['response'], TRUE);
        
        if ($result['carrier_service']['active'] == 'true'){

            echo 'The App is installed and working as carrier service inside the shop checkout <Make a nice page here or redirect to shop now>';
        }
        else {
	        //throw new NotFoundException('Could not find that post');
	        //Not quite sure about CakePHP3 errors - doc not too clear on error in class
	        //test
	        throw new NotFoundException('Activation result not found');
        }
    }           


}
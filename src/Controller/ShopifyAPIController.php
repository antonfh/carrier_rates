<?php	
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Network\Request;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ShopifyAPIController extends AppController
{

    private  $redirect_uri;
    private  $shop;
    private  $api_key;
    private  $scope;
    private  $shared_secret;
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ShopifyCurl');
        $this->redirect_uri = Configure::read('CTRACK.APP_URI');
        $this->shop = Configure::read('CTRACK.MY_SHOP');
        $this->api_key = Configure::read('CTRACK.API_KEY');
        $this->scope = Configure::read('CTRACK.SCOPE');
        $this->shared_secret = Configure::read('CTRACK.APP_SHARED_SECRET');
    }


    /**
    * Step 1 End node - links to Shop and request to link app with store
    * TODO: Add checks and other fields as per Shopify docs
    */
	public function index()
    {
        $this->response->type('json');
        $this->autoRender = false;
       
        try 
        {
            if ($this->request->is('get') && 
            isset($this->request->query['shop'])) 
            {
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
    * TODO: Read up on Shopify API - any checks - What to do now - check App and Shop integration 
    * 
    *  STEP 2:
    *  CALL The Shop back - Shopify to return an Access Token with this Call
    */
    public function activate()
    {
        //Setup Cake to not return a template and use json (as from what I understand)
        $this->response->type('json');
        $this->autoRender = false;

        //Get some variables pre-calls and from rquests
        $code = $this->request->query['code'];
        $hmac = $this->request->query["hmac"];
        $timestamp = $this->request->query["timestamp"];
        $signature = $this->request->query["signature"];
        $signature_data = $this->shared_secret . 
                            "code=" . $code . 
                            "hmac=" . $hmac . 
                            "shop=". $this->shop . 
                            ".myshopify.comtimestamp=" . $timestamp;

        //Query only valid if signature from shopify match
        if (md5($signature_data) == $signature) {
 
            echo "Validated";
            $query = array(
                "Content-type" => "application/json",
                "client_id" => $this->api_key,
                "client_secret" => $this->shared_secret,
                "code" => $code
            );
      
            //$curlCall = new ShopifyCurl();
            $shopify_response = $this->ShopifyCurl->shopify_call(NULL, $this->shop, "/admin/oauth/access_token", $query, 'POST');

            $shopify_response = json_decode($shopify_response['response'], TRUE);
            $token = $shopify_response['access_token'];
            echo $token;

            $shops = TableRegistry::get('Shops');
            $query = $shops->query();

            $query->insert(['shop_domain','token','created'])->values([
                'shop_domain' => $this->shop,
                'token' => $token,
                'created' => Time::now()
            ])
            ->execute();
                
   
        }
        
    }




    




}
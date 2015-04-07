<?php	
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Network\Request;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

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

        //Query only valide if signature from shopify match
        if (md5($signature_data) == $signature) {
 
            echo "Validated";
            $query = array(
                "Content-type" => "application/json",
                "client_id" => $this->api_key,
                "client_secret" => $this->shared_secret,
                "code" => $code
            );
      
            $shopify_response = $this->shopify_call(NULL, $this->shop, "/admin/oauth/access_token", $query, 'POST');

            $shopify_response = json_decode($shopify_response['response'], TRUE);
            $token = $shopify_response['access_token'];
            echo $token;

            $shops = TableRegistry::get('Shops');
           $query = $shops->query();

           $query->insert(['shop_domain','token','created'])->values([
                'shop_domain' => $this->shop,
                'token' => $token,
                'created' => 'now()'
            ])
           ->execute();
                
   
        }
        
    }




    /**
    * HAVING NO LUCK GETTING SHOPIFY TO GIVE ME ANY TOKEN - TRY SOME CURL 
    * THIS IS NOT MY OWN FUNCTION
    */
    private function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) {
    
        // Build URL
        $url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
        
        if (!is_null($query) && in_array($method, array('GET',  'DELETE'))) $url = $url . "?" . http_build_query($query);
        
            // Configure cURL
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, TRUE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
            // curl_setopt($curl, CURLOPT_SSLVERSION, 3);
            curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            
            // Setup headers
            $request_headers[] = "";
            if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
            curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
            
            if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
                if (is_array($query)) $query = http_build_query($query);
                curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
            }
            
            // Send request to Shopify and capture any errors
            $response = curl_exec($curl);
            $error_number = curl_errno($curl);
            $error_message = curl_error($curl);
            
            // Close cURL to be nice
            curl_close($curl);
            
            // Return an error is cURL has a problem
            if ($error_number) {
                return $error_message;
            } else {
                // No error, return Shopify's response by parsing out the body and the headers
                $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
                // Convert headers into an array
                $headers = array();
                $header_data = explode("\n",$response[0]);
                $headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
                array_shift($header_data); // Remove status, we've already set it above
                foreach($header_data as $part) {
                    $h = explode(":", $part);
                    $headers[trim($h[0])] = trim($h[1]);
                }
            // Return headers and Shopify's response
            return array('headers' => $headers, 'response' => $response[1]);
        }
    }




}
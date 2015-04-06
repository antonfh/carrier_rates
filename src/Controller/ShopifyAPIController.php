<?php	
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use GuzzleHttp\Client as ShopifyGuzzleClient;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use App\Controller\AppController;
use Cake\Network\Request;

class ShopifyAPIController extends AppController
{

    /**
    * Step 1 End node - links to Shop and request to link app with store
    * TODO: Add checks and other fields as per Shopify docs
    */
	public function index()
    {
        $this->response->type('json');
        $this->autoRender = false;

        //print_r($this->request);

        try 
        {
            if ($this->request->is('get') && 
            isset($this->request->query['shop'])) 
            {
                $redirect_uri = Configure::read('CTRACK.APP_URI');

                $install_url = Configure::read('CTRACK.MY_SHOP') . "/admin/oauth/authorize?client_id=" .Configure::read('CTRACK.API_KEY') . "&scope=" . Configure::read('CTRACK.SCOPE') . "&redirect_uri=" . urlencode($redirect_uri);

               header("Location: " . $install_url);

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
        $this->response->type('json');
        $this->autoRender = false;

        $shared_secret = Configure::read('CTRACK.APP_SHARED_SECRET');
        $code = $this->request->query['code'];
        $shop = $this->request->query['shop'];
        $timestamp = $this->request->query["timestamp"];
        $signature = $this->request->query["signature"];
 
        // Compile signature data
        $signature_data = $shared_secret . "code=" . $code . "shop=". $shop . ".myshopify.comtimestamp=" . $timestamp;
 
        // Use signature data to check that the response is from Shopify or not
        //if (md5($signature_data) === $signature) {
            // VALIDATED
            echo "Validated";
            $query = array(
                "Content-type" => "application/json", // Tell Shopify that we're expecting a response in JSON format
                "client_id" => Configure::read('CTRACK.API_KEY'), // Your API key
                "client_secret" => $shared_secret, // Your app credentials (secret key)
                "code" => $code // Grab the access key from the URL
            );
            // Call our Shopify function
            $shopify_response = $this->shopify_call(NULL, $shop, "/admin/oauth/access_token", $query, 'POST');
            // Convert response into a nice and simple array
            //$shopify_response = json_decode($shopify_response['response'], TRUE);
            // Store the response
            print_r($shopify_response);
            $token = $shopify_response['access_token'];
            // Show token (DO NOT DO THIS IN YOUR PRODUCTION ENVIRONMENT)
            echo $token;
       

       /* if (isset($this->request->query['code']) && isset($this->request->query['hmac'])) 
        {


            $guzzClient = new ShopifyGuzzleClient();

            //exit;
            //print_r($guzzClient);'headers' => ['X-Foo-Header' => 'value']
            $requestgz = $guzzClient->createRequest('POST', $shop . '/admin/oauth/access_token');
            //$request->setHeader('Content-Type', 'application/json');
            $requestgz->addHeader('Content-Type', 'application/json');
            $requestgz->addHeader('Accept', 'application/json');
            $postBody = $requestgz->getBody();
            $postBody->setField('client_id', Configure::read('CTRACK.API_KEY'));
            $postBody->setField('client_secret', Configure::read('CTRACK.APP_SHARED_SECRET'));
            $postBody->setField('code', $this->request->query['code']);
            //echo $postBody->getField('code');
      // echo json_encode($postBody->getFields());
            try 
            {
                //Shopify API doc says to make POST request with client_id, secret and the code back
                //$response = $guzzClient->send($requestgz);              
               //$token = $response->json();
                

               
            } 
            catch (Guzzle\Http\Exception\BadResponseException $e) {
                echo 'Uh oh! ' . $e->getMessage();
            }
            catch (Exception $e) 
            {
                return $e;
            }   

            print_r($response);
             print_r($this->request);

        }
        */
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
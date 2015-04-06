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
                $redirect_uri = "http://".Configure::read('CTRACK.APP_URI');

                $install_url = "https://" . $this->request->query['shop'] . ".myshopify.com/admin/oauth/authorize?client_id=" .Configure::read('CTRACK.API_KEY') . "&scope=" . Configure::read('CTRACK.SCOPE') . "&redirect_uri=" . urlencode($redirect_uri);

               header("Location: " . $install_url);
die();
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
        if (md5($signature_data) === $signature) {
            // VALIDATED
            echo "Validated";
            die();
        } else {
            // NOT VALIDATED - Someone is being shady!
        }

        if (isset($this->request->query['code']) && isset($this->request->query['hmac'])) 
        {


            $guzzClient = new ShopifyGuzzleClient();

            //exit;
            //print_r($guzzClient);
            $request = $guzzClient->createRequest('POST', 'http://' . $this->request->query['shop'] . '/admin/oauth/access_token');
            //$request->setHeader('Content-Type', 'application/json');
            $postBody = $request->getBody();
            $postBody->setField('client_id', Configure::read('CTRACK.API_KEY'));
            $postBody->setField('client_secret', Configure::read('CTRACK.APP_SHARED_SECRET'));
            $postBody->setField('code', $this->request->query['code']);
            //echo $postBody->getField('code');
      // echo json_encode($postBody->getFields());
            try 
            {

                //Shopify API doc says to make POST request with client_id, secret and the code back
                $response = $guzzClient->send($request);
                                
print_r($request);
               
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

        }
    }





}
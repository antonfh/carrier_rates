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
                $this->redirect('https://' . 
                    $this->request->query['shop'] . 
                    '/admin/oauth/authorize?client_id=' . Configure::read('CTRACK.API_KEY') .
                    '&scope=' . Configure::read('CTRACK.SCOPE') .
                    '&redirect_uri=' . Configure::read('CTRACK.APP_URI')
                );
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
        //$this->response->type('json');
        $this->autoRender = false;

        if (isset($this->request->query['code']) && isset($this->request->query['hmac'])) 
        {
            $guzzClient = new ShopifyGuzzleClient();

            //exit;
            //print_r($guzzClient);
            $request = $guzzClient->createRequest('POST', 'http://' . $this->request->query['shop'] . '/admin/oauth/access_token');
            $postBody = $request->getBody();

            $postBody->setField('client_id', Configure::read('CTRACK.API_KEY'));
            echo $postBody->getField('client_id');

            $postBody->setField('client_secret', Configure::read('CTRACK.APP_SHARED_SECRET'));
            echo $postBody->getField('client_secret');

            $postBody->setField('code', $this->request->query['code']);
            echo $postBody->getField('code');

            echo json_encode($postBody->getFields());
            try 
            {

                //Shopify API doc says to make POST request with client_id, secret and the code back
                $response = $guzzClient->send($request);
                     
               $body = $response->getBody();
                echo $body;
                exit;


                 $response = $client->post('https://'.$this->request->query['shop'].'/admin/carrier_services.json', [
                                    'headers' => ['Accept' => 'application/json',
                                        'X-Shopify-Access-Token' => $response['access_token'],
                                        'Content-Type' => 'application/json'
                                    ],
                                    'body' => '{"carrier_service": {"name": "carrier_rates","callback_url": "http://carrier2.anton.co.za/carrier/rates","format": "json","service_discovery": true}}']);
                    print_r($response);
                
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
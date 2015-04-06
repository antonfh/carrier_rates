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
            print_r($guzzClient);
            try 
            {

                //Shopify API doc says to make POST request with client_id, secret and the code back
                $response = $guzzClient->post(
                    'http://' . $this->request->query['shop'] . '/admin/oauth/access_token', [
                        'body' => [
                            'client_id' => Configure::read('CTRACK.API_KEY'),
                            'client_secret' => Configure::read('CTRACK.APP_SHARED_SECRET'),
                            'code' => $this->request->query['code'],
                        ]
                    ]
                );
                    print_r($response->json()); 
               
                
            } 
            catch (Guzzle\Http\Exception\BadResponseException $e) {
                echo 'Uh oh! ' . $e->getMessage();
            }
            catch (Exception $e) 
            {
                return $e;
            }   
           
        }
    }





}
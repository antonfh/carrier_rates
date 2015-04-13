<?php
namespace App\Controller;

use App\Controller\AppController;
use GuzzleHttp\Client;
use Cake\Core\Configure;
//require ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * CarrierRates Controller
 *
 * @property \App\Model\Table\CarrierRatesTable $CarrierRates
 * 
 */
class CarrierRatesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ShopifyCarrierAPI');
    }
    

    /**
     * postRates POST  method - get JSON string for carrier rates
     *
     * @return json $response return the carrier rates to the Carrier Rates app for the shop
     */
    public function postRates() {
        $this->response->type('json');
        $this->autoRender = false;
        $postal_code = $this->request->data['rate']['destination']['postal_code'];
echo $postal_code;
        //Check for Shop Id and then get Token
        if (isset($this->request->query['shop'])) {
            $tokenObj = new ShopifyCarrierAPIComponent();
            $token = $tokenObj->getToken($this->request->query['shop']);
        }

       //print_r($code);
        if ($postal_code > 0){
           $query['rates'] = $this->CarrierRates
            ->find()
            ->select(['id', 'service_name', 'service_code', 'total_price', 'currency','min_delivery_date', 'max_delivery_date'])
            ->where(['postal_code =' => $postal_code])
            ->order(['created' => 'DESC']);
        }
        else{
           
            //Return all the Carrier Rates with Call to CarrierRates Table obj, and use VirtualField defined in Entity Model CarrierRate
            $query['rates'] = $this->CarrierRates
            ->find('all',
                array(
                        "fields" => array('id', 
                            'service_name', 
                            'service_code', 
                            'total_price', 
                            'currency',
                            'min_delivery_date' => "date_format(CURDATE(),'%Y-%m-%d %H:%i:%s +0200')",
                            'max_delivery_date' => "date_format( ADDDATE(CURDATE(), INTERVAL (FLOOR( 1 + RAND( ) *4 )) DAY),'%Y-%m-%d %H:%i:%s +0200')"
                            ),
                        "order" => array("created ASC"),
                        "conditions" => array('postal_code >' => '0')
                        ));
        }

        
        $this->response->body(json_encode($query));
        return $this->response;
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('carrierRates', $this->paginate($this->CarrierRates));
        $this->set('_serialize', ['carrierRates']);
    }

    /**
     * View method
     *
     * @param string|null $id Carrier Rate id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $carrierRate = $this->CarrierRates->get($id, [
            'contain' => []
        ]);
        $this->set('carrierRate', $carrierRate);
        $this->set('_serialize', ['carrierRate']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $carrierRate = $this->CarrierRates->newEntity();
        if ($this->request->is('post')) {
            $carrierRate = $this->CarrierRates->patchEntity($carrierRate, $this->request->data);
            if ($this->CarrierRates->save($carrierRate)) {
                $this->Flash->success('The carrier rate has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The carrier rate could not be saved. Please, try again.');
            }
        }
        $this->set(compact('carrierRate'));
        $this->set('_serialize', ['carrierRate']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Carrier Rate id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $carrierRate = $this->CarrierRates->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $carrierRate = $this->CarrierRates->patchEntity($carrierRate, $this->request->data);
            if ($this->CarrierRates->save($carrierRate)) {
                $this->Flash->success('The carrier rate has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The carrier rate could not be saved. Please, try again.');
            }
        }
        $this->set(compact('carrierRate'));
        $this->set('_serialize', ['carrierRate']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Carrier Rate id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $carrierRate = $this->CarrierRates->get($id);
        if ($this->CarrierRates->delete($carrierRate)) {
            $this->Flash->success('The carrier rate has been deleted.');
        } else {
            $this->Flash->error('The carrier rate could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}

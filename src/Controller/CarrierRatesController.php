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
     * @return void
     */
    public function postRates() {
        $this->response->type('json');
        $this->autoRender = false;
        $postal_code = $this->request->params['pass'];
        //$code = $this->request->data['rate'];
        //$code[0];
        echo "API". Configure::read('CTRACK.APP_SHARED_SECRET');
       //print_r($code);
        if ($postal_code){
           $query['rates'] = $this->CarrierRates
            ->find()
            ->select(['id', 'service_name', 'service_code', 'total_price', 'currency'])
            ->where(['postal_code =' => $postal_code[0]])
            ->order(['created' => 'DESC']);
        }
        else{
            $query['rates'] = $this->CarrierRates
            ->find()
            ->select(['id', 'service_name', 'service_code', 'total_price', 'currency'])
            ->where(['postal_code >' => 0])
            ->order(['created' => 'DESC']);
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

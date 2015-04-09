<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\ORM\Table;


/**
* Shopy Carrier API class to get Token
*/
class ShopifyCarrierAPIComponent extends Component
{
	//private $theToken;
	
	public function initialize() 
	{
        parent::initialize();
    }

	/**
	* TODO: Use this and not Controller for the API auth - later 
	* 
	* @param array $data - do something with data
	* @return void
	*/	
	public function checkAccess($data) 
	{
	}

	/**
	* Get the Token from the Shops table using CakePHP3 ORM find method
	* 
	* @param String $domain shop name to find in Shops table to get Token
	*/
	protected function getToken($domain)
	{

		$query['token'] = $this->Shops
            ->find()
            ->select(['token'])
            ->where(['shop_domain =' => $domain])
            ->order(['created' => 'DESC']);
	}

	/**
	* Set the Token from to Shops table using insert method
	* 
	* @param String $shop shop name for this token save
	* @param String $token The return Token to validate our app
	*/
	protected function setToken($shop, $token)
	{

		$this->theToken = $token;

		//Setup our Shops query to save the token record to the Shops database
		$shops = TableRegistry::get('Shops');
        $query = $shops->query();

        $query->insert(['shop_domain','token','created'])->values([
            'shop_domain' => $shop,
            'token' => $token,
            'created' => Time::now()
        ])
        ->execute(); 
	}
	
}
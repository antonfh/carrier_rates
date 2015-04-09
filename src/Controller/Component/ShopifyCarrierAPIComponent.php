<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ShopifyCarrierAPIComponent extends Component
{
	private $theToken;
	/*
	* TODO: Use this and not Controller for the API auth - later 
	*/	
	public function checkAccess($data)
	{
		
	}

	protected function getToken($domain){

		$query['token'] = $this->Shops
            ->find()
            ->select(['token'])
            ->where(['shop_domain =' => $domain])
            ->order(['created' => 'DESC']);
	}
}
<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CarrierRate Entity.
 */
class CarrierRate extends Entity
{
    //Define my virtual fields to create a dynamic min and max date for the payload required 
    //by the Carrier Rates service
    protected $_virtual = ['min_delivery_date','max_delivery_date'];      
                          

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'service_name' => true,
        'service_code' => true,
        'total_price' => true,
        'currency' => true,
        'postal_code' => true,
    ];
}

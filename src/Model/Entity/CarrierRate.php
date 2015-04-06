<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CarrierRate Entity.
 */
class CarrierRate extends Entity
{

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

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CourierRate Entity.
 */
class CourierRate extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'int_postal_code' => true,
        'dec_rate_sameday' => true,
        'dec_rate_twoday' => true,
    ];
}

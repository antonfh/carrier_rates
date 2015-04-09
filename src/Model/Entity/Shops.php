<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shops Entity.
 */
class Shops extends Entity
{

   

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'shop_domain' => true,
        'token' => true,
        'created' => true,
        'updated' => true,
        'is_active' => true,
    ];
}

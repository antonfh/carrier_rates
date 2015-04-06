<?php
namespace App\Model\Table;

use App\Model\Entity\CarrierRate;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CarrierRates Model
 */
class CarrierRatesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('carrier_rates');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('service_name', 'create')
            ->notEmpty('service_name')
            ->requirePresence('service_code', 'create')
            ->notEmpty('service_code')
            ->add('total_price', 'valid', ['rule' => 'decimal'])
            ->requirePresence('total_price', 'create')
            ->notEmpty('total_price')
            ->requirePresence('currency', 'create')
            ->notEmpty('currency')
            ->add('postal_code', 'valid', ['rule' => 'numeric'])
            ->requirePresence('postal_code', 'create')
            ->notEmpty('postal_code');

        return $validator;
    }


    public function findPostCode($id){
        $query['rates'] = $this->CarrierRates
            ->find()
            ->select(['id', 'service_name', 'service_code', 'total_price', 'currency'])
            ->where(['postal_code =' => $postal_code[0]])
            ->order(['created' => 'DESC']);

            return $query;
    }
}

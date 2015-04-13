<?php

use Phinx\Migration\AbstractMigration;

class CarrierRatesNewMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $exists = $this->hasTable('carrier_rates');

        if (!$exists) {
            $courier_rates = $this->table('carrier_rates');
            $courier_rates
                    ->addColumn('service_name', 'string', array('limit' => 25))
                    ->addColumn('service_code', 'string', array('limit' => 5))
                    ->addColumn('total_price', 'integer', array('limit' => 4))
                    ->addColumn('currency', 'string', array('limit' => 4))
                    ->addColumn('postal_code', 'integer', array('limit' => 4))
                    ->addColumn('created', 'datetime')
                    ->addColumn('updated', 'datetime', array('null' => true))
                  ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('carrier_rates');
    }
}
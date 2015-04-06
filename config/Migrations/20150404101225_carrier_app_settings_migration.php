<?php

use Phinx\Migration\AbstractMigration;

class CarrierAppSettingsMigration extends AbstractMigration
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
        $exists = $this->hasTable('carrier_app');

        if (!$exists) {
            $courier_rates = $this->table('carrier_app');
            $courier_rates
                    ->addColumn('api_key', 'string', array('limit' => 225))
                    ->addColumn('redirect_url', 'string', array('limit' => 255))
                    ->addColumn('permissions', 'string', array('limit' => 255))
                    ->addColumn('shared_secret', 'string', array('limit' => 255))
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

    }
}
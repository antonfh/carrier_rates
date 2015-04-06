<?php

use Phinx\Migration\AbstractMigration;

class CourierStoreSettingsMigration extends AbstractMigration
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
        $exists = $this->hasTable('store_settings');

        if (!$exists) {
            $app_settings = $this->table('store_settings');
            $app_settings
                    ->addColumn('access_token', 'string', array('limit' => 255))
                    ->addColumn('store_name', 'string', array('limit' => 255))
                    ->addColumn('created', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
                    ->addColumn('updated', 'datetime', array('null' => true))
                  ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('store_settings');
    }
}
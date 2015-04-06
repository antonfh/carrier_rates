<?php

use Phinx\Migration\AbstractMigration;

class CourierAppSettingsMigration extends AbstractMigration
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
        $exists = $this->hasTable('app_settings');

        if (!$exists) {
            $app_settings = $this->table('app_settings');
            $app_settings
                    ->addColumn('api_key', 'string', array('limit' => 100))
                    ->addColumn('redirect_url', 'string', array('limit' => 255))
                    ->addColumn('permissions', 'string', array('limit' => 255))
                    ->addColumn('shared_secret', 'string', array('limit' => 255))
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
        $this->dropTable('app_settings');
    }
}
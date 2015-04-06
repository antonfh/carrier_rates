<?php

use Phinx\Migration\AbstractMigration;

class CarrierRatesSeedMigration extends AbstractMigration
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
        if($exists) {
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'SD1', 175, 'ZAR', '1000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'TD1', 100, 'ZAR', '1000')");   
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'SD1', 125, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'TD1', 75, 'ZAR', '2000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'SD1', 150, 'ZAR', '3000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'TD1', 95, 'ZAR', '3000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'SD1', 130, 'ZAR', '4000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'TD1', 65, 'ZAR', '4000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'SD1', 160, 'ZAR', '5000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'TD1', 125, 'ZAR', '5000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'SD1', 175, 'ZAR', '2000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'TD1', 100, 'ZAR', '2000')");   
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'SD1', 125, 'ZAR', '1000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'TD1', 75, 'ZAR', '1000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'SD1', 150, 'ZAR', '4000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'TD1', 95, 'ZAR', '4000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'SD1', 130, 'ZAR', '2000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'TD1', 65, 'ZAR', '2000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'SD1', 160, 'ZAR', '3000')"); 
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'TD1', 125, 'ZAR', '3000')");
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
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
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'SD1', 17500, 'ZAR', '1000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'TD1', 10000, 'ZAR', '1000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'SD1', 12500, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'TD1', 7500, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'SD1', 1500, 'ZAR', '3000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'TD1', 9500, 'ZAR', '3000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'SD1', 13000, 'ZAR', '4000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'TD1', 6500, 'ZAR', '4000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'SD1', 16000, 'ZAR', '5000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'TD1', 12500, 'ZAR', '5000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'SD1', 17500, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SuperCarriers', 'TD1', 10000, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'SD1', 12500, 'ZAR', '1000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SlowJoeCarriers', 'TD1', 7500, 'ZAR', '1000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'SD1', 15000, 'ZAR', '4000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('FlashCarriers', 'TD1', 9500, 'ZAR', '4000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'SD1', 13000, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('SwiftCarriers', 'TD1', 6500, 'ZAR', '2000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'SD1', 16000, 'ZAR', '3000')");
            $this->execute("insert into carrier_rates(service_name, service_code,total_price,currency,postal_code) values ('LastCarrier', 'TD1', 12500, 'ZAR', '3000')");
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
	    $exists = $this->hasTable('carrier_rates');
	    if($exists) {
		    $this->execute("DELETE FROM carrier_rates");
	    }
    }
}
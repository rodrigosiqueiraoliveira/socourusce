<?php

$installer = $this;

$db = Mage::getSingleton('core/resource')->getConnection('core_write');
$prefix = Mage::getConfig()->getTablePrefix();

try
{
    $db->query("
        CREATE TABLE IF NOT EXISTS `" . $prefix . "boldcron_data` (
            `merch_ref` int(10) NOT NULL,
            `bpag_id` int(10) NOT NULL,
            PRIMARY KEY (`merch_ref`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
        
        CREATE TABLE IF NOT EXISTS `" . $prefix . "boldcron_boleto_data` (
            `order_id` int(10) NOT NULL,
            `url` varchar(500) NOT NULL,
            PRIMARY KEY (`order_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
    ");
}
catch(Exception $ex)
{
	exit('Exception: ' . $ex->getMessage());
}

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'cc_parcelas', 'int(3) NULL');
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'cc_parcelas', 'int(3) NULL');

$installer->endSetup();

?>
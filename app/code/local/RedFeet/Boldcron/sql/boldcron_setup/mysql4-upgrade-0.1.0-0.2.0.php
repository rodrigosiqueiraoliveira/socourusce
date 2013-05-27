<?php
// here are the table updates for this module e.g.:
$installer = $this;
$installer->startSetup();

/*
$installer->run("

ALTER TABLE `{$this->getTable('sales/quote_payment')}` ADD `cc_parcelas` INT( 3 ) NOT NULL;

ALTER TABLE `{$this->getTable('sales/order_payment')}` ADD `cc_parcelas` INT( 3 ) NOT NULL;

");
*/

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'cc_parcelas', 'int(3) NULL');
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'cc_parcelas', 'int(3) NULL');

$installer->endSetup();
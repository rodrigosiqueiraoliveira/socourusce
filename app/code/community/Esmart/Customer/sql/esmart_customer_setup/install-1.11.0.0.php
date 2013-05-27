<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

/**
 * Create table 'esmart_customer/sales_order'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_customer/sales_order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
        ), 'Entity Id')
    ->addForeignKey($installer->getFkName('esmart_customer/sales_order', 'entity_id', 'sales/order', 'entity_id'),
        'entity_id', $installer->getTable('sales/order'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Customer Sales Flat Order');
$installer->getConnection()->createTable($table);

/**
 * Create table 'esmart_customer/sales_order_address'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_customer/sales_order_address'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
        ), 'Entity Id')
    ->addForeignKey($installer->getFkName('esmart_customer/sales_order_address', 'entity_id', 'sales/order_address', 'entity_id'),
        'entity_id', $installer->getTable('sales/order_address'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Customer Sales Flat Order Address');
$installer->getConnection()->createTable($table);

/**
 * Create table 'esmart_customer/sales_quote'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_customer/sales_quote'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
        ), 'Entity Id')
    ->addForeignKey($installer->getFkName('esmart_customer/sales_quote', 'entity_id', 'sales/quote', 'entity_id'),
        'entity_id', $installer->getTable('sales/quote'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Customer Sales Flat Quote');
$installer->getConnection()->createTable($table);

/**
 * Create table 'esmart_customer/sales_quote_address'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_customer/sales_quote_address'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
        ), 'Entity Id')
    ->addForeignKey($installer->getFkName('esmart_customer/sales_quote_address', 'entity_id', 'sales/quote_address', 'address_id'),
        'entity_id', $installer->getTable('sales/quote_address'), 'address_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Customer Sales Flat Quote Address');
$installer->getConnection()->createTable($table);

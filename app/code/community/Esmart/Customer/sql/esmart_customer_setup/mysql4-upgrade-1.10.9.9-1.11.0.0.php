<?php

/* @var $installer Esmart_Customer_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Drop foreign keys
 */
$installer->getConnection()->dropForeignKey(
    $installer->getTable('esmart_customer/sales_order'),
    'FK_ESMART_CUSTOMER_SALES_ORDER'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('esmart_customer/sales_order_address'),
    'FK_ESMART_CUSTOMER_SALES_ORDER_ADDRESS'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('esmart_customer/sales_quote'),
    'FK_ESMART_CUSTOMER_SALES_QUOTE'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('esmart_customer/sales_quote_address'),
    'FK_ESMART_CUSTOMER_SALES_QUOTE_ADDRESS'
);

/**
 * Change columns
 */
$tables = array(
    $installer->getTable('esmart_customer/sales_order') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Entity Id'
            )
        ),
        'comment' => 'Esmart Customer Sales Flat Order'
    ),
    $installer->getTable('esmart_customer/sales_order_address') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Entity Id'
            )
        ),
        'comment' => 'Esmart Customer Sales Flat Order Address'
    ),
    $installer->getTable('esmart_customer/sales_quote') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Entity Id'
            )
        ),
        'comment' => 'Esmart Customer Sales Flat Quote'
    ),
    $installer->getTable('esmart_customer/sales_quote_address') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Entity Id'
            )
        ),
        'comment' => 'Esmart Customer Sales Flat Quote Address'
    )
);

$installer->getConnection()->modifyTables($tables);


/**
 * Add foreign keys
 */
$installer->getConnection()->addForeignKey(
    $installer->getFkName(
        'esmart_customer/sales_order',
        'entity_id',
        'sales/order',
        'entity_id'
    ),
    $installer->getTable('esmart_customer/sales_order'),
    'entity_id',
    $installer->getTable('sales/order'),
    'entity_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName(
        'esmart_customer/sales_order_address',
        'entity_id',
        'sales/order_address',
        'entity_id'
    ),
    $installer->getTable('esmart_customer/sales_order_address'),
    'entity_id',
    $installer->getTable('sales/order_address'),
    'entity_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName(
        'esmart_customer/sales_quote',
        'entity_id',
        'sales/quote',
        'entity_id'
    ),
    $installer->getTable('esmart_customer/sales_quote'),
    'entity_id',
    $installer->getTable('sales/quote'),
    'entity_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName(
        'esmart_customer/sales_quote_address',
        'entity_id',
        'sales/quote_address',
        'address_id'
    ),
    $installer->getTable('esmart_customer/sales_quote_address'),
    'entity_id',
    $installer->getTable('sales/quote_address'),
    'address_id'
);

$installer->endSetup();

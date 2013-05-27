<?php
/**
 * Smart Commerce do Brasil
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@e-smart.com.br so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.e-smart.com.br for more information.
 *
 * @category    Esmart
 * @package     Esmart_Logging
 * @copyright   Copyright (c) 2012 Smart Commerce do Brasil. (http://www.e-smart.com.br)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
 /**
 * 
 *
 * @category    Esmart
 * @package     Esmart_Logging
 * @author      Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
/**
 * Create table 'esmart_logging/event'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_logging/event'))
    ->addColumn('log_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Log Id')
    ->addColumn('ip', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Ip address')
    ->addColumn('x_forwarded_ip', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Real ip address if visitor used proxy')
    ->addColumn('event_code', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        ), 'Event Code')
    ->addColumn('time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Even date')
    ->addColumn('action', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
        ), 'Event action')
    ->addColumn('info', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Additional information')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 15, array(
        ), 'Status')
    ->addColumn('user', Varien_Db_Ddl_Table::TYPE_TEXT, 40, array(
        ), 'User name')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'User Id')
    ->addColumn('fullaction', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
        ), 'Full action description')
    ->addColumn('error_message', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Error Message')
    ->addIndex($installer->getIdxName('esmart_logging/event', array('user_id')),
        array('user_id'))
    ->addIndex($installer->getIdxName('esmart_logging/event', array('user')),
        array('user'))
    ->addForeignKey($installer->getFkName('esmart_logging/event', 'user_id', 'admin/user', 'user_id'),
        'user_id', $installer->getTable('admin/user'), 'user_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Logging Event');
$installer->getConnection()->createTable($table);

/**
 * Create table 'esmart_logging/event_changes'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('esmart_logging/event_changes'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Esmart logging id')
    ->addColumn('source_name', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
        ), 'Logged Source Name')
    ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Logged event id')
    ->addColumn('source_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Logged Source Id')
    ->addColumn('original_data', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Logged Original Data')
    ->addColumn('result_data', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Logged Result Data')
    ->addIndex($installer->getIdxName('esmart_logging/event_changes', array('event_id')),
        array('event_id'))
    ->addForeignKey($installer->getFkName('esmart_logging/event_changes', 'event_id', 'esmart_logging/event', 'log_id'),
        'event_id', $installer->getTable('esmart_logging/event'), 'log_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Esmart Logging Event Changes');
$installer->getConnection()->createTable($table);

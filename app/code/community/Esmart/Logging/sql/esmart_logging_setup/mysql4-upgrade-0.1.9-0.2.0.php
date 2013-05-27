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
 * @author      Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 * @copyright   Copyright (c) 2012 Smart Commerce do Brasil. (http://www.e-smart.com.br)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$tableLog = $this->getTable('esmart_logging/event');
$tableLogChanges = $this->getTable('esmart_logging/event_changes');

$installer->getConnection()->addColumn($tableLog, 'x_forwarded_ip', "bigint(20) unsigned NULL DEFAULT NULL AFTER `ip`");

$installer->run("DROP TABLE IF EXISTS `{$tableLogChanges}`");
$installer->run("CREATE TABLE `".$tableLogChanges."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(150) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `original_data` text NOT NULL,
  `result_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `FK_LOGGING_EVENT_CHANGES_EVENT_ID` FOREIGN KEY (`event_id`) REFERENCES `{$tableLog}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$installer->getConnection()->addColumn($tableLog, 'error_message', "tinytext DEFAULT NULL");
$installer->endSetup();

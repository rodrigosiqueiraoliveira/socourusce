<?php

/**
 * Madia Adyen Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Madia
 * @package	Madia_Adyen
 * @copyright	Copyright (c) 2012 Madia (http://www.madia.nl)
 * @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   Payment Gateway
 * @package    Madia_Adyen
 * @author     Omar,Muhsin <info@madia.nl>
 * @property   Madia B.V
 * @copyright  Copyright (c) 2012 Madia BV (http://www.madia.nl)
 */
class Madia_Adyen_Model_Source_IdealIssuerList {

    public function toOptionArray() {
        return $this->_toOptionArray('https://live.adyen.com/hpp/idealbanklist.shtml');
    }

    protected function _toOptionArray($url) {
        $options = array();
        $liveXml = $this->_getFileGetContents($url);
        if ($liveXml === false)
            return $options;
        $liveXml = new SimpleXMLElement($liveXml);
        foreach ($liveXml as $idealIssuer) {
            $id = trim((string) $idealIssuer->bank_id);
            $label = trim((string) $idealIssuer->bank_name);
            $options[] = array('value' => $id . DS . $label, 'label' => $label);
        }
        return $options;
    }

    /**
     * Since v0.1.0.9r1
     * @param type $url
     * @return boolean 
     */
    protected function _getFileGetContents($url) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);
        curl_close($c);
        if ($contents)
            return $contents;
        return false;
    }

}
<?php

/**
 * Customer abstract model
 *
 */
abstract class Esmart_Customer_Model_Sales_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Save new attribute
     *
     * @param Mage_Customer_Model_Attribute $attribute
     * @return Esmart_Customer_Model_Sales_Abstract
     */
    public function saveNewAttribute(Mage_Customer_Model_Attribute $attribute)
    {
        $this->_getResource()->saveNewAttribute($attribute);
        return $this;
    }

    /**
     * Delete attribute
     *
     * @param Mage_Customer_Model_Attribute $attribute
     * @return Esmart_Customer_Model_Sales_Abstract
     */
    public function deleteAttribute(Mage_Customer_Model_Attribute $attribute)
    {
        $this->_getResource()->deleteAttribute($attribute);
        return $this;
    }

    /**
     * Attach extended data to sales object
     *
     * @param Mage_Core_Model_Abstract $sales
     * @return Esmart_Customer_Model_Sales_Abstract
     */
    public function attachAttributeData(Mage_Core_Model_Abstract $sales)
    {
        $sales->addData($this->getData());
        return $this;
    }

    /**
     * Save extended attributes data
     *
     * @param Mage_Core_Model_Abstract $sales
     * @return Esmart_Customer_Model_Sales_Abstract
     */
    public function saveAttributeData(Mage_Core_Model_Abstract $sales)
    {
        $this->addData($sales->getData())
            ->setId($sales->getId())
            ->save();

        return $this;
    }

    /**
     * Processing object before save data.
     * Need to check if main entity is already deleted from the database:
     * we should not save additional attributes for deleted entities.
     *
     * @return Esmart_Customer_Model_Sales_Abstract
     */
    protected function _beforeSave()
    {
        if ($this->_dataSaveAllowed && !$this->_getResource()->isEntityExists($this)) {
            $this->_dataSaveAllowed = false;
        }
        return parent::_beforeSave();
    }
}

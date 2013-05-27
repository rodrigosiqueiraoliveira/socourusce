<?php

class RedFeet_Boldcron_Model_Observer
{
    public function capture() {
        echo "Capture order called!";
        Mage::Log("capture order called!");
    }
}
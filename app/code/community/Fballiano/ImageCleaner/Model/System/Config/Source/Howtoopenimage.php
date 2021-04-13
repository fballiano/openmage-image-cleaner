<?php

class Fballiano_ImageCleaner_Model_System_Config_Source_Howtoopenimage
{
    public function toOptionArray()
    {
        $helper = Mage::helper('fballiano_imagecleaner');
        return array(
            array(
                'value' => Fballiano_ImageCleaner_Helper_Data::OPEN_IMAGE_SAME_WINDOW,
                'label' => $helper->__('Same window'),
            ),
            array(
                'value' => Fballiano_ImageCleaner_Helper_Data::OPEN_IMAGE_NEW_WINDOW,
                'label' => $helper->__('New window'),
            ),
        );
    }
}
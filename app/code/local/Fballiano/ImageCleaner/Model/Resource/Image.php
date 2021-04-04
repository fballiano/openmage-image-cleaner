<?php
 
class Fballiano_ImageCleaner_Model_Resource_Image extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('fballiano_imagecleaner/image', 'image_id');
    }
}
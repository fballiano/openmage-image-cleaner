<?php

class Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $url = Mage::getBaseUrl('media');
        switch ($row->getEntityTypeId()) {
            case 3:
                $url .= "catalog/category/";
                break;
            case 4:
                $url .= "catalog/product/";
                break;
        }

        return "<img src='{$url}{$row->getPath()}' style='max-width: 200px' />";
    }
}
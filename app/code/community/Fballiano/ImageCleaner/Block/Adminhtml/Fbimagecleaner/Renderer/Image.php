<?php
/**
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FBalliano
 * @package    FBalliano_ImageCleaner
 * @copyright  Copyright (c) Fabrizio Balliano (http://fabrizioballiano.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $url = Mage::getBaseUrl('media');
        $max_width = Mage::getStoreConfig('admin/fb_image_cleaner/thumbnail_max_width');

        switch ($row->getEntityTypeId()) {
            case Mage::getModel('catalog/category')->getResource()->getTypeId():
                $url .= "catalog/category/";
                break;
            case Mage::getModel('catalog/product')->getResource()->getTypeId():
                $url .= "catalog/product/";
                break;
            case -Mage::getModel('catalog/category')->getResource()->getTypeId():
                $url .= "catalog/category/cache/";
                break;
            case -Mage::getModel('catalog/product')->getResource()->getTypeId():
                $url .= "catalog/product/cache/";
                break;
            case -98:
                $url .= "wysiwyg/";
                break;
        }

        $return = "<img src='{$url}{$row->getPath()}' style='max-width:{$max_width}px' />";
        if (Mage::getStoreConfig('admin/fb_image_cleaner/enable_image_click')) {
            $return = "<a href='{$url}{$row->getPath()}' target=_blank>$return</a>";
        }

        return $return;
    }
}

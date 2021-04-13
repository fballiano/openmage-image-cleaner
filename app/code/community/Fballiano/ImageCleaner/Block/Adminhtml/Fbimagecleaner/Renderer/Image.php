<?php

/**
 * FBalliano
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this Module to
 * newer versions in the future.
 *
 * @category   FBalliano
 * @package    FBalliano_ImageCleaner
 * @copyright  Copyright (c) 2021 Fabrizio Balliano (http://fabrizioballiano.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $url = Mage::getBaseUrl('media');
        $max_width = Mage::getStoreConfig('admin/fb_image_cleaner/thumbnail_max_width');

        switch ($row->getEntityTypeId()) {
            case 3:
                $url .= "catalog/category/";
                break;
            case 4:
                $url .= "catalog/product/";
                break;
            case 98:
                $url .= "wysiwyg/";
                break;
        }

        $return = "<img src='{$url}{$row->getPath()}' style='max-width:{$max_width}px' />";
        if (Mage::getStoreConfig('admin/fb_image_cleaner/enable_image_click')) {
            $target = Mage::getStoreConfig('admin/fb_image_cleaner/how_to_open_image') == Fballiano_ImageCleaner_Helper_Data::OPEN_IMAGE_SAME_WINDOW ? '' : 'target=_blank';
            $return = "<a href='{$url}{$row->getPath()}' {$target}>$return</a>";
        }

        return $return;
    }
}
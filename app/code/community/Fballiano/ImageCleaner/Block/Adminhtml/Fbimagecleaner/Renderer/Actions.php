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
class Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Renderer_Actions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $links = array();

        $links[] = sprintf(
            '<a href="%s">%s</a>',
            $this->getUrl('*/*/download', array('image_id' => $row->getId())),
            $this->__('Download')
        );

        $links[] = sprintf(
            '<a href="%s">%s</a>',
            $this->getUrl('*/*/delete', array('image_id' => $row->getId())),
            $this->__('Delete')
        );

        return implode(' | ', $links);
    }
}
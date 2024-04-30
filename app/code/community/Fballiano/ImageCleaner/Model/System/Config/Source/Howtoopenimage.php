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
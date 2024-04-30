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
class Fballiano_ImageCleaner_Block_Adminhtml_Image extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'fballiano_imagecleaner';
        $this->_controller = 'adminhtml_fbimagecleaner';
        $this->_headerText = $this->__('Image Cleaner');

        parent::__construct();
        $this->_removeButton('add');

        $this->_addButton(
            'sync_product', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync Products'),
                'onclick' => "setLocation('{$this->getUrl('*/*/syncproduct')}')"
            )
        );

        $this->_addButton(
            'sync_category', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync Categories'),
                'onclick' => "setLocation('{$this->getUrl('*/*/synccategory')}')"
            )
        );

        $this->_addButton(
            'sync_wysiwyg', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync WYSIWYG'),
                'onclick' => "setLocation('{$this->getUrl('*/*/syncwysiwyg')}')"
            )
        );

        $this->_addButton(
            'sync_product_cache', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync Products Cache'),
                'onclick' => "setLocation('{$this->getUrl('*/*/syncproductcache')}')"
            )
        );

        $this->_addButton(
            'flush_media_tmp', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Flush media/tmp'),
                'onclick' => "setLocation('{$this->getUrl('*/*/flushmediatmp')}')"
            )
        );

        $this->_addButton(
            'flush_media_import', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Flush media/import'),
                'onclick' => "setLocation('{$this->getUrl('*/*/flushmediaimport')}')"
            )
        );

        $this->_addButton(
            'flush_var_export', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Flush var/export'),
                'onclick' => "setLocation('{$this->getUrl('*/*/flushvarexport')}')"
            )
        );

        $this->_addButton(
            'flush_var_importexport', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Flush var/importexport'),
                'onclick' => "setLocation('{$this->getUrl('*/*/flushvarimportexport')}')"
            )
        );

        $this->_addButton(
            'reset', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Reset IC Data'),
                'onclick' => "setLocation('{$this->getUrl('*/*/reset')}')"
            )
        );
    }
}


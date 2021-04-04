<?php

class Fballiano_ImageCleaner_Block_Adminhtml_Image extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'fballiano_imagecleaner';
        $this->_controller = 'adminhtml_fbimagecleaner';
        $this->_headerText = $this->__('Image Cleaner');
        // $this->_addButtonLabel  = $this->__('Add Button Label');

        parent::__construct();
        $this->_removeButton('add');

        $this->_addButton(
            'sync_category', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync Category Images'),
                'onclick' => "setLocation('{$this->getUrl('*/*/synccategory')}')",
                'class' => 'add'
            )
        );

        $this->_addButton(
            'sync_product', array(
                'label' => Mage::helper('fballiano_imagecleaner')->__('Sync Product Images'),
                'onclick' => "setLocation('{$this->getUrl('*/*/syncproduct')}')",
                'class' => 'add'
            )
        );
    }
}


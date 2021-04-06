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
class Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('Fballiano_Imagecleaner_grid');
        $this->setDefaultSort('image_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('fballiano_imagecleaner/image')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('image_id', array(
            'header' => $this->__('ID'),
            'type' => 'text',
            'width' => '100px',
            'index' => 'image_id'
        ));

        $this->addColumn('entity_type', array(
            'header' => $this->__('Entity Type'),
            'type' => 'options',
            'width' => '100px',
            'align'=> 'center',
            'index' => 'entity_type_id',
            'options' => array(
                3 => $this->__('Category'),
                4 => $this->__('Product'),
                98 => $this->__('WYSIWYG'),
            )
        ));

        $this->addColumn('filename', array(
            'header' => $this->__('File Name'),
            'type' => 'text',
            'index' => 'path',
            'sortable' => false
        ));

        $this->addColumn('image', array(
            'header' => $this->__('Image'),
            'type' => 'text',
            'width' => '250px',
            'align'=> 'center',
            'index' => 'path',
            'sortable' => false,
            'renderer' => 'Fballiano_ImageCleaner_Block_Adminhtml_Fbimagecleaner_Renderer_Image',
        ));

        $this->addColumn('actions', array(
            'type' => 'action',
            'align'=> 'center',
            'filter' => false,
            'sortable' => false,
            'index' => 'image_id',
            'actions' => array(
                array(
                    'caption' => $this->__('Delete'),
                    'url' => array('base' => '*/*/delete'),
                    'field' => 'image_id',
                    'confirm' => $this->__('Are you sure?')
                )
            ),
        ));

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return false;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('image_id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));
        return $this;
    }
}

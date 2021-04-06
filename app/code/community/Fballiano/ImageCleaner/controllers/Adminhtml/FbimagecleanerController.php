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
class Fballiano_ImageCleaner_Adminhtml_FbimagecleanerController extends Mage_Adminhtml_Controller_Action
{
    public function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/system/tools/fballiano_imagecleaner');
    }

    public function indexAction()
    {
        $this->_title("Image Cleaner");
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('fballiano_imagecleaner/adminhtml_image'));
        $this->renderLayout();
    }

    public function synccategoryAction()
    {
        $entity_type_id = 3;
        $media_dir = Mage::getBaseDir('media') . '/catalog/category';
        $resource = Mage::getSingleton('core/resource');
        $db = $resource->getConnection('core_read');

        $attributes = $db->fetchCol("SELECT attribute_id FROM {$resource->getTableName('eav_attribute')} WHERE entity_type_id={$entity_type_id} AND frontend_input='image'");
        $attributes = implode(",", $attributes);
        $db_images = $db->fetchCol("SELECT value FROM {$resource->getTableName('catalog_category_entity_varchar')} WHERE value IS NOT NULL AND LENGTH(value)>0 AND entity_type_id={$entity_type_id} AND attribute_id IN ($attributes)");

        $fs_images = scandir($media_dir);
        foreach ($fs_images as $k => $fs_image) {
            if (!is_file("$media_dir/$fs_image")) unset($fs_images[$k]);
        }

        $unused_images = array_diff($fs_images, $db_images);
        if ($unused_images) {
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $already_seen_images = $db->fetchCol("SELECT path FROM {$cleaner_table} WHERE entity_type_id={$entity_type_id}");
            $unused_images = array_diff($unused_images, $already_seen_images);
            if ($unused_images) {
                foreach ($unused_images as $unused_image) {
                    $db->insert($cleaner_table, array(
                        'entity_type_id' => $entity_type_id,
                        'path' => $unused_image
                    ));
                }
            }
        }

        $this->_redirect('*/*');
    }

    public function syncproductAction()
    {
        $entity_type_id = 4;
        $media_dir = Mage::getBaseDir('media') . '/catalog/product';
        $resource = Mage::getSingleton('core/resource');
        $db = $resource->getConnection('core_read');

        $attributes = $db->fetchCol("SELECT attribute_id FROM {$resource->getTableName('eav_attribute')} WHERE entity_type_id={$entity_type_id} AND frontend_input='media_image'");
        $attributes = implode(",", $attributes);
        $db_images = $db->fetchCol("SELECT value FROM {$resource->getTableName('catalog_product_entity_varchar')} WHERE value IS NOT NULL AND LENGTH(value)>0 AND entity_type_id={$entity_type_id} AND attribute_id IN ($attributes) AND value <> 'no_selection'");
        if ($db_images) $db_images = substr_replace($db_images, '', 0, 1);

        $media_gallery = $db->fetchCol("SELECT value FROM {$resource->getTableName('catalog_product_entity_media_gallery')} WHERE value IS NOT NULL AND LENGTH(value)>0");
        if ($media_gallery) $media_gallery = substr_replace($media_gallery, '', 0, 1);

        $fs_images = Mage::helper('fballiano_imagecleaner')->scandirRecursive($media_dir);
        $fs_images = str_replace("{$media_dir}/", '', $fs_images);

        $unused_images = array_diff($fs_images, $db_images, $media_gallery);
        if ($unused_images) {
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $already_seen_images = $db->fetchCol("SELECT path FROM {$cleaner_table} WHERE entity_type_id={$entity_type_id}");
            $unused_images = array_diff($unused_images, $already_seen_images);
            if ($unused_images) {
                foreach ($unused_images as $unused_image) {
                    $db->insert($cleaner_table, array(
                        'entity_type_id' => $entity_type_id,
                        'path' => $unused_image
                    ));
                }
            }
        }

        $this->_redirect('*/*');
    }

    public function syncwysiwygAction()
    {
        $entity_type_id = 98;
        $media_dir = Mage::getBaseDir('media') . '/wysiwyg';
        $resource = Mage::getSingleton('core/resource');
        $db = $resource->getConnection('core_read');

        $db_images = $db->fetchCol("SELECT content FROM {$resource->getTableName('cms_page')} UNION SELECT content FROM {$resource->getTableName('cms_block')}");
        $fs_images = Mage::helper('fballiano_imagecleaner')->scandirRecursive($media_dir);
        $fs_images = str_replace(Mage::getBaseDir() . '/', '', $fs_images);

        $unused_images = array();
        foreach ($fs_images as $fs_image) {
            foreach ($db_images as $db_image) {
                if (stripos($db_image, $fs_image) === false) {
                    $unused_images[] = $fs_image;
                    continue 2;
                }
            }
        }

        if ($unused_images) {
            $unused_images = str_replace('media/wysiwyg/', '', $unused_images);
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $already_seen_images = $db->fetchCol("SELECT path FROM {$cleaner_table} WHERE entity_type_id={$entity_type_id}");
            $unused_images = array_diff($unused_images, $already_seen_images);
            if ($unused_images) {
                foreach ($unused_images as $unused_image) {
                    $db->insertIgnore($cleaner_table, array(
                        'entity_type_id' => $entity_type_id,
                        'path' => $unused_image
                    ));
                }
            }
        }

        $this->_redirect('*/*');
    }

    public function deleteAction()
    {
        $image_id = $this->getRequest()->getParam('image_id');
        if (is_numeric($image_id)) {
            $resource = Mage::getSingleton('core/resource');
            $db = $resource->getConnection('core_read');
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $image = $db->fetchRow("SELECT * FROM {$cleaner_table} WHERE image_id=?", $image_id);
            if ($image) {
                $helper = Mage::helper('fballiano_imagecleaner');
                $image_path = $helper->getMediaDirByEntityTypeId($image['entity_type_id']) . $image["path"];
                if (unlink($image_path)) $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
            }
        }

        $this->_redirect('*/*');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if ($ids) {
            $resource = Mage::getSingleton('core/resource');
            $db = $resource->getConnection('core_read');
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $helper = Mage::helper('fballiano_imagecleaner');
            foreach ($ids as $image_id) {
                $image = $db->fetchRow("SELECT * FROM {$cleaner_table} WHERE image_id=?", $image_id);
                if ($image) {
                    $image_path = $helper->getMediaDirByEntityTypeId($image['entity_type_id']) . $image["path"];
                    if (unlink($image_path)) $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                }
            }
        }

        $this->_redirect('*/*');
    }
}

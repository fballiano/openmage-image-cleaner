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

        if (!file_exists($media_dir) || !is_dir($media_dir)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('"media/catalog/category" folder does not exist.'));
            $this->_redirect('*/*');
            return;
        }

        $attributes = $db->fetchCol("SELECT attribute_id FROM {$resource->getTableName('eav_attribute')} WHERE entity_type_id={$entity_type_id} AND frontend_input='image'");
        $attributes = implode(",", $attributes);
        $db_images = $db->fetchCol("SELECT value FROM {$resource->getTableName('catalog_category_entity_varchar')} WHERE value IS NOT NULL AND LENGTH(value)>0 AND entity_type_id={$entity_type_id} AND attribute_id IN ($attributes)");

        $fs_images = Mage::helper('fballiano_imagecleaner')->scandirRecursive($media_dir);
        $fs_images = str_replace("{$media_dir}/", '', $fs_images);

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

        if (!file_exists($media_dir) || !is_dir($media_dir)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('"media/catalog/product" folder does not exist.'));
            $this->_redirect('*/*');
            return;
        }

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
        $helper = Mage::helper('fballiano_imagecleaner');

        if (!file_exists($media_dir) || !is_dir($media_dir)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('"media/wysiwyg" folder does not exist.'));
            $this->_redirect('*/*');
            return;
        }

        $db_images = $db->fetchCol("SELECT content FROM {$resource->getTableName('cms_page')} UNION SELECT content FROM {$resource->getTableName('cms_block')} UNION SELECT template_text FROM {$resource->getTableName('core_email_template')} UNION SELECT template_styles FROM {$resource->getTableName('core_email_template')}");
        $css_files = $helper->getAllCSSFilesContents();
        $fs_images = $helper->scandirRecursive($media_dir);
        $fs_images = str_replace(Mage::getBaseDir() . '/media/', '', $fs_images);

        $used_images = array();
        foreach ($fs_images as $fs_image) {
            foreach ($db_images as $db_image) {
                if (stripos($db_image, $fs_image) !== false) {
                    $used_images[] = $fs_image;
                    break;
                }
            }
            foreach ($css_files as $css_file) {
                if (stripos($css_file, $fs_image) !== false) {
                    $used_images[] = $fs_image;
                    break;
                }
            }
        }

        $unused_images = array_diff($fs_images, $used_images);
        if ($unused_images) {
            $unused_images = str_replace('wysiwyg/', '', $unused_images);
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
                if (!file_exists($image_path)) {
                    $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                    $this->_redirect('*/*');
                    return;
                }
                if (unlink($image_path)) {
                    $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('It was not possible to delete one or more files from the filesystem.'));
                }
            }
        }

        $this->_redirect('*/*');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        $error_message_thrown = false;
        if ($ids) {
            $resource = Mage::getSingleton('core/resource');
            $db = $resource->getConnection('core_read');
            $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
            $helper = Mage::helper('fballiano_imagecleaner');
            foreach ($ids as $image_id) {
                $image = $db->fetchRow("SELECT * FROM {$cleaner_table} WHERE image_id=?", $image_id);
                if ($image) {
                    $image_path = $helper->getMediaDirByEntityTypeId($image['entity_type_id']) . $image["path"];
                    if (!file_exists($image_path)) {
                        $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                        continue;
                    }
                    if (unlink($image_path)) {
                        $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                    } elseif (!$error_message_thrown) {
                        $error_message_thrown = true;
                        Mage::getSingleton('adminhtml/session')->addError($this->__('It was not possible to delete one or more files from the filesystem.'));
                    }
                }
            }
        }

        $this->_redirect('*/*');
    }

    public function downloadAction()
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
                if (!file_exists($image_path)) {
                    $db->query("DELETE FROM {$cleaner_table} WHERE image_id=?", $image_id);
                    Mage::getSingleton('adminhtml/session')->addError($this->__('Image not found.'));
                    $this->_redirect('*/*');
                    return;
                }

                $this->_prepareDownloadResponse(basename($image_path), file_get_contents($image_path));
            }
        }
    }

    public function exportCsvAction()
    {
        $fileName   = 'unused_images.csv';
        $grid       = $this->getLayout()->createBlock('fballiano_imagecleaner/adminhtml_fbimagecleaner_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportExcelAction()
    {
        $fileName   = 'unused_images.xml';
        $grid       = $this->getLayout()->createBlock('fballiano_imagecleaner/adminhtml_fbimagecleaner_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    public function resetAction()
    {
        $resource = Mage::getSingleton('core/resource');
        $db = $resource->getConnection('core_read');
        $cleaner_table = $resource->getTableName('fb_imagecleaner_image');
        $db->query("TRUNCATE TABLE {$cleaner_table}");
        $this->_redirect('*/*');
    }
}
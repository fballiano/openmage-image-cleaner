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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();
$tableName = $installer->getTable('fb_imagecleaner_image');

$connection->dropTable($tableName);

$table = $connection
    ->newTable($tableName)
    ->addColumn('image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ], 'Image ID')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
        'nullable' => false,
    ], 'Entity Type ID')
    ->addColumn('path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [
        'nullable' => false,
    ], 'Path')
    ->addIndex(
        $installer->getIdxName('fb_imagecleaner_image', ['entity_type_id', 'path'], Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        ['entity_type_id', 'path'],
        ['type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE]
    );

$connection->createTable($table);

$installer->endSetup();

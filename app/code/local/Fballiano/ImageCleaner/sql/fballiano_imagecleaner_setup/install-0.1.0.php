<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('fb_imagecleaner_image')};
CREATE TABLE `{$this->getTable( 'fb_imagecleaner_image' )}` (
	`image_id` int unsigned AUTO_INCREMENT,
	`entity_type_id` smallint (5) unsigned NOT NULL,
	`path` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `entity_type_id` (`entity_type_id`,`path`)
);");
$installer->endSetup();

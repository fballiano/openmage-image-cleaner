<?php

class Fballiano_ImageCleaner_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function scandirRecursive($dir)
    {
        $result = array();
        $root = scandir($dir);
        foreach ($root as $value) {
            if ($value === '.' or $value === '..' or $value === 'cache' or $value === 'watermark' or $value === 'optimized') continue;
            if (is_file("$dir/$value")) {
                $result[] = "$dir/$value";
                continue;
            }

            foreach ($this->scandirRecursive("$dir/$value") as $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

    public function getMediaDirByEntityTypeId($entity_type_id)
    {
        $media_dir = Mage::getBaseDir('media') . '/';

        if ($entity_type_id == 3) return "{$media_dir}catalog/category/";
        if ($entity_type_id == 4) return "{$media_dir}catalog/product/";

        return $media_dir;
    }
}
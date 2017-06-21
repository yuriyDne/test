<?php
class Autoloader {
    static public function loader($className) {
        if (!class_exists($className)) {
            $filename = str_replace("\\", '/', $className) . ".php";
            if (file_exists($filename)) {
                include($filename);
                if (class_exists($className)) {
                    return true;
                }
            } else {
                throw new \RuntimeException("file not found: $filename");
            }
        }
    }
}
spl_autoload_register('Autoloader::loader');

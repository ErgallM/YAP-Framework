<?php
namespace Yap;

class Loader
{
    static $_path = array();

    static public function addPath($prefix, $path)
    {
        $incPath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($incPath as $p) {
            if (true == is_dir($p . '/' . $path)) {
                self::$_path[$prefix] = realpath($p . '/' . $path);
                return true;
            }
        }

        throw new \Exception("Can't found path '" . $path . "'");
        return false;
    }

    static public function addPaths($paths)
    {
        foreach ($paths as $prefix => $path) {
            self::addPath($prefix, $path);
        }
        return true;
    }

    static public function loadClass($className, $dirs = null)
    {
        echo 'start load class = ' . $className . PHP_EOL;
        if (class_exists($className, false) || interface_exists($className, false)) {
            return true;
        }

        $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        self::loadFile($file, $dirs, true);

        if (!class_exists($className, false) && !interface_exists($className, false)) {
            throw new \Exception("File '$file' does not exist or class '$className' was not found in the file");
        }
    }

    static public function loadFile($filename, $dirs = null, $once = false)
    {
        $incPath = false;
        if (!empty($dir) && (is_array($dirs) || is_string($dirs))) {
            if (is_array($dirs)) {
                $dirs = implode(PATH_SEPARATOR, $dirs);
            }
            $incPath = get_include_path();
            set_include_path($dirs . PATH_SEPARATOR . $incPath);
        }

        if ($once) {
            include_once $filename;
        } else {
            include $filename;
        }

        if ($incPath) {
            set_include_path($incPath);
        }

        return true;
    }

    static public function initAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . '\Loader::loadClass');
    }
}

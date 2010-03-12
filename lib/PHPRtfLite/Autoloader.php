<?php
/* 
    PHPRtfLite
    Copyright 2010 Steffen Zeidler <sigma_z@web.de>

    This file is part of PHPRtfLite.

    PHPRtfLite is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PHPRtfLite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with PHPRtfLite.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Class for autoloading PHPRtfLite classes.
 * @version     1.0.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Autoloader
{
    /**
     * base dir of the PHPRtfLite package
     * @var string
     */
    static protected $_baseDir;


    /**
     * Sets the base dir, where PHPRtfLite classes can be found
     * @param string $dir
     */
    static public function setBaseDir($dir) {
        self::$_baseDir = $dir;
    }

    /**
     * loads PHPRtfLite class
     *
     * @param  string   $className
     * @return boolean  returns true, if class could be loaded
     */
    static public function autoload($className) {
        $classFile = self::$_baseDir . '/' . str_replace('_', '/', $className) . '.php';

        if (is_file($classFile)) {
            require $classFile;
            return true;
        }

        echo $classFile;

        return false;
    }

}
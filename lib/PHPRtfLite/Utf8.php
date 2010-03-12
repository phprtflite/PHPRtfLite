<?php
/* 
    PHPRtfLite
    Copyright 2007-2008 Denis Slaveckij <info@phprtf.com>
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
 * UTF8 class with static functions that converts utf8 characters into rtf utf8 entities.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Utf8 {

    /**
     * Converts text with utf8 characters into rtf utf8 entites.
     *
     * @param string $str
     */
    static public function getUnicodeEntities($str) {
        return self::unicodeToEntitiesPreservingAscii(self::utf8ToUnicode($str));
    }

    /**
     * @see http://www.randomchaos.com/documents/?source=php_and_unicode
     *
     * Gets unicode for each character
     *
     * @return array
     */
    static private function utf8ToUnicode($str) {
        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen($str); $i++ ) {
           $thisValue = ord($str[$i]);

            if ($thisValue < 128) {
                $unicode[] = $thisValue;
            }
            else {
                if (count( $values ) == 0) {
                    $lookingFor = $thisValue < 224 ? 2 : 3;
                }

                $values[] = $thisValue;

                if (count($values) == $lookingFor) {
                    $number = $lookingFor == 3
                              ? (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64)
                              : (($values[0] % 32) * 64) + ($values[1] % 64);

                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;
                }
            }
        }

        return $unicode;
    }

    /**
     * Converts text with utf8 characters into rtf utf8 entites preserving ascii.
     *
     * @param  string $unicode
     *
     * @return string
     */
    static private function unicodeToEntitiesPreservingAscii($unicode) {
        $entities = '';

        foreach ($unicode as $value) {
            if ($value != 65279) {
               $entities .= $value > 127 
                            ? '\uc0\u' . $value . ' '
                            : chr($value);
            }
        }

        return $entities;
    }

}
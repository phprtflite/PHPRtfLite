<?php
/*
    PHPRtfLite
    Copyright 2007-2008 Denis Slaveckij <sinedas@gmail.com>
    Copyright 2010-2012s Steffen Zeidler <sigma_z@sigma-scripts.de>

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
 * @version     1.2
 * @author      Denis Slaveckij <sinedas@gmail.com>
 * @author      Steffen Zeidler <sigma_z@sigma-scripts.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010-2012 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Utf8
{

    /**
     * converts text with utf8 characters into rtf utf8 entites
     *
     * @param string $text
     */
    public static function getUnicodeEntities($text, $inCharset)
    {
        if ($inCharset != 'UTF-8') {
            if (extension_loaded('iconv')) {
                $text = iconv($inCharset, 'UTF-8//TRANSLIT', $text);
            }
            else {
                throw new PHPRtfLite_Exception('Iconv extension is not available! '
                                             . 'Activate this extension or use UTF-8 encoded texts!');
            }
        }
        $text = self::utf8ToUnicode($text);
        return self::unicodeToEntitiesPreservingAscii($text);
    }


    /**
     * gets unicode for each character
     * @see http://www.randomchaos.com/documents/?source=php_and_unicode
     *
     * @return array
     */
    private static function utf8ToUnicode($str)
    {
        $unicode = array();

        for ($i = 0; $i < mb_strlen($str); $i++ ) {
            $char = mb_substr($str, $i, 1);
            $unicode[] = mb_ord($char);
        }

        return $unicode;
    }


    /**
     * converts text with utf8 characters into rtf utf8 entites preserving ascii
     *
     * @param  string $unicode
     * @return string
     * @see https://www.oreilly.com/library/view/rtf-pocket-guide/9781449302047/ch01.html#unicode_in_rtf
     */
    private static function unicodeToEntitiesPreservingAscii($unicode)
    {
        $entities = '';

        foreach ($unicode as $value) {
            if ($value != 65279) {
                if ($value <= 127) {
                    $entities .= chr($value);
                } else if ($value < 255) {
                    $entities .= '\uc0{\u' . $value . '}';
                } else if ($value <= 32768) {
                    $entities .= '\uc1{\u' . $value . '}';
                } else if ($value <= 65535) {
                    $entities .= '\uc1{\u' . ($value - 65536) . '}';
                } else {
                    $hex = bin2hex(mb_convert_encoding(mb_chr($value), 'UTF-16', 'UTF-8'));
                    $hexs = str_split($hex, 4); // split by 2 bytes
                    $encodedChars = array_map(function ($hex) {
                        return '\u' . (hexdec($hex) - 65536) . '?';
                    }, $hexs);
                    $entities .= '\uc1{' . implode('', $encodedChars) . '}';
                }
            }
        }

        return $entities;
    }

}
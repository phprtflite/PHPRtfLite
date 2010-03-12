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
 * Class for border format.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Border
 */
class PHPRtfLite_Border_Format {

    /**
     * constants for border format type
     */
    const TYPE_SINGLE   = 'single';
    const TYPE_DOT      = 'dot';
    const TYPE_DASH     = 'dash';
    const TYPE_DOTDASH  = 'dotdash';

    /**
     * @var integer
     */
    protected $_size;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_color;

    /**
     * @var integer
     */
    protected $_space;
    
    /**
     * Constructor
     * @param   integer     $size   size of border
     * @param   string      $color  color of border (example '#ff0000' or '#f00')
     * @param   string      $type   represented by class constants PHPRtfLite_Border_Format::TYPE_*<br>
     *   Possible values:<br>
     *     TYPE_SINGLE  => 'single'<br>
     *     TYPE_DOT      = 'dot'<br>
     *     TYPE_DASH     = 'dash'<br>
     *     TYPE_DOTDASH  = 'dotdash'<br>
     * @param   float       $space  space between borders and the paragraph
     */
    public function __construct($size = 0, $color = null, $type = null, $space = 0) {
        $this->_size = $size * PHPRtfLite::SPACE_IN_POINTS;
        $this->_type = $type;

        if ($color) {
            $this->_color = PHPRtfLite::convertHexColorToRtf($color);
        }

        $this->_space = round($space * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets rtf code of not colored part of border fotmat.
     * @return string rtf code
     */
    public function getNotColoredPartOfContent() {
          return $this->getTypeAsRtfCode()
               . '\brdrw' . $this->_size
               . '\brsp' . $this->_space;
    }

    /**
     * Gets border format type as rtf code
     *
     * @return string rtf code
     */
    public function getTypeAsRtfCode() {
        switch ($this->_type) {
            case self::TYPE_DOT:
                return '\brdrdot';

            case self::TYPE_DASH:
                return '\brdrdash';

            case self::TYPE_DOTDASH:
                return '\brdrdashd';

            default:
                return '\brdrs';
        }
    }


    /**
     * Gets border format type
     * 
     * @return string
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Sets border format type
     *
     * @param   string      $type   represented by class constants PHPRtfLite_Border_Format::TYPE_*<br>
     *   Possible values:<br>
     *     TYPE_SINGLE  => 'single'<br>
     *     TYPE_DOT      = 'dot'<br>
     *     TYPE_DASH     = 'dash'<br>
     *     TYPE_DOTDASH  = 'dotdash'<br>
     */
    public function setType($type) {
        $this->_type = $type;
    }

    /**
     * Gets border color
     *
     * @return string
     */
    public function getColor() {
        return $this->_color;
    }

    /**
     * Sets border color
     *
     * @param string $color
     */
    public function setColor($color) {
        $this->_color = $color;
    }

    /**
     * Gets border size
     *
     * @return integer
     */
    public function getSize() {
        return $this->_size;
    }

    /**
     * Sets border size
     *
     * @param integer $size
     */
    public function setSize($size) {
        $this->_size = $size;
    }

    /**
     * Gets border space
     *
     * @return float
     */
    public function getSpace() {
        return $this->_space;
    }

    /**
     * Sets border space
     *
     * @param float $space
     */
    public function setSpace($space) {
        $this->_space = $space;
    }

}
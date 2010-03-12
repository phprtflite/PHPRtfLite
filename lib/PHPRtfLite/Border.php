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
 * Class for creating borders within rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Border
 */
class PHPRtfLite_Border {

    /**
     * @var PHPRtfLite_Border_Format
     */
    protected $_borderLeft;

    /**
     * @var PHPRtfLite_Border_Format
     */
    protected $_borderRight;

    /**
     * @var PHPRtfLite_Border_Format
     */
    protected $_borderTop;

    /**
     * @var PHPRtfLite_Border_Format
     */
    protected $_borderBottom;


    /**
     * Creates border by defining border format
     *
     * @param   integer $size   size of border
     * @param   string  $color  color of border (example '#ff0000' or '#f00')
     * @param   string  $type   represented by class constants PHPRtfLite_Border_Format::TYPE_*<br>
     *   Possible values:<br>
     *     PHPRtfLite_Border_Format::TYPE_SINGLE  => 'single'<br>
     *     PHPRtfLite_Border_Format::TYPE_DOT      = 'dot'<br>
     *     PHPRtfLite_Border_Format::TYPE_DASH     = 'dash'<br>
     *     PHPRtfLite_Border_Format::TYPE_DOTDASH  = 'dotdash'
     *
     * @param   float   $space  space between borders and the paragraph
     * @param   boolean $left   left border
     * @param   boolean $top    top border
     * @param   boolean $right  right border
     * @param   boolean $bottom bottom border
     *
     * @return  PHPRtfLite_Border
     */
    static public function create($size = 0, $color = null, $type = null, $space = 0,
                                  $left = true, $top = true, $right = true, $bottom = true)
    {
        $border = new self();
        $border->setBorders(new PHPRtfLite_Border_Format($size, $color, $type, $space), $left, $top, $right, $bottom);

        return $border;
    }

    /**
     * Constructor
     * @param PHPRtfLite_Border_Format $left
     * @param PHPRtfLite_Border_Format $top
     * @param PHPRtfLite_Border_Format $right
     * @param PHPRtfLite_Border_Format $bottom
     */
    public function __construct(PHPRtfLite_Border_Format $left  = null,
                                PHPRtfLite_Border_Format $top   = null,
                                PHPRtfLite_Border_Format $right = null,
                                PHPRtfLite_Border_Format $bottom = null)
    {
        $this->_borderLeft      = $left;
        $this->_borderTop       = $top;
        $this->_borderRight     = $right;
        $this->_borderBottom    = $bottom;
    }


    /**
     * Sets border format of element.
     * @param   PHPRtfLite_Border_Format $borderFormat
     * @param   boolean $left
     * @param   boolean $top
     * @param   boolean $right
     * @param   boolean $bottom
     */
    public function setBorders(PHPRtfLite_Border_Format $borderFormat,
                               $left = true, $top = true, $right = true, $bottom = true)
    {
        if ($left) {
            $this->_borderLeft  = $borderFormat;
        }

        if ($top) {
            $this->_borderTop   = $borderFormat;
        }

        if ($right) {
            $this->_borderRight = $borderFormat;
        }

        if ($bottom) {
            $this->_borderBottom = $borderFormat;
        }
    }

    /**
     * Sets different border formats
     *
     * @param PHPRtfLite_Border_Format $formatLeft
     * @param PHPRtfLite_Border_Format $formatTop
     * @param PHPRtfLite_Border_Format $formatRight
     * @param PHPRtfLite_Border_Format $formatBottom
     */
    public function setBorderAll(PHPRtfLite_Border_Format $formatLeft,
                                 PHPRtfLite_Border_Format $formatTop,
                                 PHPRtfLite_Border_Format $formatRight,
                                 PHPRtfLite_Border_Format $formatBottom)
    {
        $this->_borderLeft      = $formatLeft;
        $this->_borderTop       = $formatTop;
        $this->_borderRight     = $formatRight;
        $this->_borderBottom    = $formatBottom;
    }

    /**
     * Sets border format for left border.
     * @param PHPRtfLite_Border_Format $borderFormat
     */
    public function setBorderLeft(PHPRtfLite_Border_Format $borderFormat) {
        $this->_borderLeft = $borderFormat;
    }

    /**
     * Gets border format of left border.
     * @return PHPRtfLite_Border_Format
     */
    public function getBorderLeft() {
        return $this->_borderLeft;
    }

    /**
     * Sets border format for right border.
     * @param PHPRtfLite_Border_Format $borderFormat
     */
    public function setBorderRight(PHPRtfLite_Border_Format $borderFormat) {
        $this->_borderRight = $borderFormat;
    }

    /**
     * Gets border format of right border.
     * @return PHPRtfLite_Border_Format
     */
    public function getBorderRight() {
        return $this->_borderRight;
    }

    /**
     * Sets border format for top border.
     * @param PHPRtfLite_Border_Format $borderFormat
     */
    public function setBorderTop(PHPRtfLite_Border_Format $borderFormat) {
        $this->_borderTop = $borderFormat;
    }

    /**
     * Gets border format of top border.
     * @return PHPRtfLite_Border_Format
     */
    public function getBorderTop() {
        return $this->_borderTop;
    }

    /**
     * Sets border format for bottom border.
     * @param PHPRtfLite_Border_Format $borderFormat
     */
    public function setBorderBottom(PHPRtfLite_Border_Format $borderFormat) {
        $this->_borderBottom = $borderFormat;
    }

    /**
     * Gets border format of bottom border.
     * @return PHPRtfLite_Border_Format
     */
    public function getBorderBottom() {
        return $this->_borderBottom;
    }

    /**
     * Gets rtf code of object.
     * @param   PHPRtfLite  $rtf
     * @param   string $type rtf code part
     * 
     * @return  string rtf code
     */
    public function getContent(PHPRtfLite $rtf, $type = '\\') {
        $content = '';

        if ($this->_borderLeft && $this->_borderLeft->getSize() > 0) {
            $content .= $type . 'brdrl' . $this->getBorderRtf($this->_borderLeft, $rtf);
        }
        if ($this->_borderRight && $this->_borderRight->getSize() > 0) {
            $content .= $type . 'brdrr' . $this->getBorderRtf($this->_borderRight, $rtf);
        }
        if ($this->_borderTop && $this->_borderTop->getSize() > 0) {
            $content .= $type . 'brdrt' . $this->getBorderRtf($this->_borderTop, $rtf);
        }
        if ($this->_borderBottom && $this->_borderBottom->getSize() > 0) {
            $content .= $type . 'brdrb' . $this->getBorderRtf($this->_borderBottom, $rtf);
        }

        return $content;
    }

    /**
     * Gets rtf code for border
     * @param PHPRtfLite_Border_Format  $borderFormat
     * @param PHPRtfLite                $rtf
     *
     * @return string rtf code
     */
    private function getBorderRtf(PHPRtfLite_Border_Format $borderFormat, PHPRtfLite $rtf) {
        $borderRtf = $borderFormat->getNotColoredPartOfContent();
        $color = $borderFormat->getColor();

        if ($color) {
            $rtf->addColor($color);
            $borderRtf .= '\brdrcf' . $rtf->getColor($color);
        }

        return $borderRtf . ' ';
    }

}
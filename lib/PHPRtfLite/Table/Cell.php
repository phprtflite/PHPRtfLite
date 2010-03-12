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
 * Class for creating cells of table in rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>, Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2009 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Table
 */
class PHPRtfLite_Table_Cell extends PHPRtfLite_Container {

    /**
     * constants for rotation directions
     */
    const ROTATE_RIGHT  = 'right';
    const ROTATE_LEFT   = 'left';

    /**
     * @var PHPRtfLite_Table
     */
    protected $_table;

    /**
     * row index within the table
     * @var integer
     */
    protected $_rowIndex;

    /**
     * column index within the table
     * @var integer
     */
    protected $_columnIndex;

    /**
     * horizontal alignment
     * @var string
     */
    protected $_alignment;

    /**
     * vertical alignment
     * @var string
     */
    protected $_verticalAlignment;

    /**
     * font used for the cell
     * @var PHPRtfLite_Font
     */
    protected $_font;

    /**
     * rotation direction
     * @var string
     */
    protected $_rotateTo;

    /**
     * background color of the cell
     * @var string
     */
    protected $_backgroundColor;

    /**
     * border of the cell
     * @var PHPRtfLite_Border
     */
    protected $_border;

    /**
     * true, if cell is merged horizontally
     * @var boolean
     */
    protected $_horizontalMerged = false;
    
    /**
     * true, if cell is merged vertically
     * @var boolean
     */
    protected $_verticalMerged = false;

    /**
     * ture, if cell merge starts with this cell
     * @var <type>
     */
    protected $_verticalMergeStart = false;

    /**
     * width of cell
     * @var float
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_pard = '\pard \intbl ';

    /**
     * Constructor of cell.
     *
     * @param   PHPRtfLite_Table    $table          table instance
     * @param   integer             $rowIndex       row index for cell in table
     * @param   integer             $columnIndex    column index for cell in table
     */
    public function __construct(PHPRtfLite_Table $table, $rowIndex, $columnIndex) {
        $this->_table       = $table;
        $this->_rowIndex    = $rowIndex;
        $this->_columnIndex = $columnIndex;
        $this->_rtf         = $table->getRtf();
    }

    /**
     * Overriden. Does nothing. Nesting cells are not suported in current version.
     */
    public function addTable($alignment = PHPRtfLite_Table::ALIGN_LEFT) {
    }

    /**
     * Sets text alignment for cell. The method PHPRtfLite_Table->writeToCell() overrides it with alignment of an instance of PHPRtfLite_ParFormat.
     * @param   string  $alignment  alignment of cell<br>
     *   Possible values:<br>
     *     TEXT_ALIGN_LEFT      => 'left'       - left alignment<br>
     *     TEXT_ALIGN_CENTER    => 'center'     - center alignment<br>
     *     TEXT_ALIGN_RIGHT     => 'right'      - right alignment<br>
     *     TEXT_ALIGN_JUSTIFY   => 'justify'    - justify alignment
     */
    public function setTextAlignment($alignment = self::TEXT_ALIGN_LEFT) {
        $this->_alignment = $alignment;
    }

    /**
     * Gets text alignment for cell.
     *
     * @return string
     */
    public function getTextAlignment() {
        return $this->_alignment;
    }

    /**
     * Sets font to a cell. The method PHPRtfLite_Table->writeToCell() overrides it with another Font.
     * 
     * @param   PHPRtfLite_Font $font
     */
    public function setFont(PHPRtfLite_Font $font) {
        $this->_font = $font;
    }

    /**
     * Gets font of cell.
     * 
     * @return PHPRtfLite_Font
     */
    public function getFont() {
        return $this->_font;
    }

    /**
     * Sets vertical alignment of cell
     *
     * @param   string  $verticalAlignment vertical alignment of cell (default top).<br>
     *   Possible values:<br>
     *     VERTICAL_ALIGN_TOP       => 'top'    - top alignment<br>
     *     VERTICAL_ALIGN_CENTER    => 'center' - center alignment<br>
     *     VERTICAL_ALIGN_BOTTOM    => 'bottom' - bottom alignment
     */
    public function setVerticalAlignment($verticalAlignment = self::VERTICAL_ALIGN_TOP) {
        $this->_verticalAlignment = $verticalAlignment;
    }

    /**
     * Gets vertical alignment of cell
     *
     * @return string
     */
    public function getVerticalAlignment() {
        return $this->_verticalAlignment;
    }

    /**
     * Rotates text of cell
     *
     * @param   string  $rotateTo  direction of rotation.<br>
     *   Possible values:<br>
     *     ROTATE_RIGHT => 'right'  - right<br>
     *     ROTATE_LEFT  => 'left'   - left
     */
    public function rotateTo($rotateTo = self::ROTATE_RIGHT) {
        $this->_rotateTo = $rotateTo;
    }

    /**
     * Gets rotation direction of cell
     * 
     * @return string
     */
    public function getRotateTo() {
        return $this->_rotateTo;
    }

    /**
     * Sets background color
     *
     * @param string $color background color
     */
    public function setBackgroundColor($color) {
        $color = PHPRtfLite::convertHexColorToRtf($color);
        $this->_table->getRtf()->addColor($color);
        $this->_backgroundColor = $color;
    }

    /**
     * Gets background color
     *
     * @return string
     */
    public function getBackgroundColor() {
        return $this->_backgroundColor;
    }

    /**
     * Sets that cell is horizontal merged
     *
     * @param boolean $merged
     */
    public function setHorizontalMerged($merged = true) {
        $this->_horizontalMerged = $merged;
    }

    /**
     * Returns true, if cell is horizontal merged
     *
     * @return boolean
     */
    public function isHorizontalMerged() {
        return $this->_horizontalMerged;
    }

    /**
     * Sets that cell is vertical merged
     *
     * @param boolean $merged
     */
    public function setVerticalMerged($merged = true) {
        $this->_verticalMerged = $merged;
    }

    /**
     * Returns true, if cell is horizontal merged
     *
     * @return boolean
     */
    public function isVerticalMerged() {
        return $this->_verticalMerged;
    }

    /**
     * Sets cell width
     *
     * @param float $width
     */
    public function setWidth($width) {
        $this->_width = $width;
    }

    /**
     * Gets cell width
     *
     * @return float
     */
    public function getWidth() {
        return $this->_width;
    }

    /**
     * Sets border to a cell
     *
     * @param PHPRtfLite_Border $border
     */
    public function setBorder(PHPRtfLite_Border $border) {
        $borderFormatTop    = $border->getBorderTop();
        $borderFormatBottom = $border->getBorderBottom();
        $borderFormatLeft   = $border->getBorderLeft();
        $borderFormatRight  = $border->getBorderRight();

        if (!$this->_border) {
            $this->_border = new PHPRtfLite_Border();
        }
        if ($borderFormatLeft) {
            $this->_border->setBorderLeft($borderFormatLeft);
        }
        if ($borderFormatRight) {
            $this->_border->setBorderRight($borderFormatRight);
        }
        if ($borderFormatTop) {
            $this->_border->setBorderTop($borderFormatTop);
        }
        if ($borderFormatBottom) {
            $this->_border->setBorderBottom($borderFormatBottom);
        }

        if ($borderFormatTop && $this->_table->checkIfCellExists($this->_rowIndex - 1, $this->_columnIndex)) {
            $cell = $this->_table->getCell($this->_rowIndex - 1, $this->_columnIndex);
            $borderBottom = $cell->getBorder();

            if ($borderBottom == null) {
                $borderBottom = new PHPRtfLite_Border();
            }

            $borderBottom->setBorderBottom($borderFormatTop);
            $cell->setCellBorder($borderBottom);
        }

        if ($borderFormatBottom && $this->_table->checkIfCellExists($this->_rowIndex + 1, $this->_columnIndex)) {
            $cell = $this->_table->getCell($this->_rowIndex + 1, $this->_columnIndex);
            $borderTop = $cell->getBorder();

            if ($borderTop == null) {
                $borderTop = new PHPRtfLite_Border();
            }

            $borderTop->setBorderTop($borderFormatBottom);
            $cell->setCellBorder($borderTop);
        }

        if ($borderFormatLeft && $this->_table->checkIfCellExists($this->_rowIndex, $this->_columnIndex - 1)) {
            $cell = $this->_table->getCell($this->_rowIndex, $this->_columnIndex - 1);
            $borderRight = $cell->getBorder();

            if ($borderRight == null) {
                $borderRight = new PHPRtfLite_Border();
            }
            $borderRight->setBorderRight($borderFormatLeft);
            $cell->setCellBorder($borderRight);
        }

        if ($borderFormatRight && $this->_table->checkIfCellExists($this->_rowIndex, $this->_columnIndex + 1)) {
            $cell = $this->_table->getCell($this->_rowIndex, $this->_columnIndex + 1);
            $borderLeft = $cell->getBorder();

            if ($borderLeft == null) {
                $borderLeft = new PHPRtfLite_Border();
            }
            $borderLeft->setBorderLeft($borderFormatRight);
            $cell->setCellBorder($borderLeft);
        }
    }

    /**
     * Sets cell border
     * 
     * @param PHPRtfLite_Border $border 
     */
    protected function setCellBorder(PHPRtfLite_Border $border) {
        $this->_border = $border;
    }

    /**
     * Gets cell border
     *
     * @return PHPRtfLite_Border
     */
    public function getBorder() {
        return $this->_border;
    }

    /**
     * Gets row index of cell
     *
     * @return integer
     */
    public function getRowIndex() {
        return $this->_rowIndex;
    }

    /**
     * Gets column index of cell
     *
     * @return integer
     */
    public function getColumnIndex() {
        return $this->_columnIndex;
    }

    /**
     * Gets rtf code for cell
     *
     * @return string rtf code
     */
    public function getContent() {
        $content  = '{';

        switch ($this->_alignment) {
            case self::TEXT_ALIGN_LEFT:
                $content .= '\ql';
                break;

            case self::TEXT_ALIGN_CENTER:
                $content .= '\qc';
                break;

            case self::TEXT_ALIGN_RIGHT:
                $content .= '\qr';
                break;

            case self::TEXT_ALIGN_JUSTIFY:
                $content .= '\qj';
                break;
        }

        if ($this->_font) {
            $content .= $this->_font->getContent($this->_table->getRtf());
        }

        $content .= parent::getContent() . '\cell \pard }' . "\r\n";
        
        return $content;
    }


    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setTextAlignment() instead
     * @see PHPRtfLite/PHPRtfLite_Cell#setTextAlignment()
     *
     * Sets text alignment to cell. The method writeToCell overrides it with ParFormat alignment.
     * 
     * @param string $alignment alignment of cell.<br>
     *   Possible values:<br>
     *     TEXT_AlIGN_LEFT      => 'left'       - left alignment<br>
     *     TEXT_AlIGN_CENTER    => 'center'     - center alignment<br>
     *     TEXT_AlIGN_RIGHT     => 'right'      - right alignment<br>
     *     TEXT_AlIGN_JUSTIFY   => 'justify'    - justify alignment
     */
    public function setDefaultAlignment($alignment = self::TEXT_AlIGN_LEFT) {
        $this->setTextAlignment($alignment);
    }

    /**
     * @deprecated use setFont() instead
     * @see PHPRtfLite/PHPRtfLite_Cell#setFont()
     *
     * Sets font to cell. The method writeToCell overrides it with another Font.
     *
     * @param  PHPRtfLite_Font $font
     */
    public function setDefaultFont(PHPRtfLite_Font $font) {
        $this->setFont($font);
    }

    /**
     * @deprecated use rotateTo() instead
     * @see PHPRtfLite/PHPRtfLite_Cell#rotateTo()
     *
     * Rotates cell.
     *
     * @param $direction direction of rotation.<br>
     *   Possible values: <br>
     *     ROTATE_RIGHT => 'right'  - right<br>
     *     ROTATE_LEFT  => 'left'   - left
     */
    public function rotate($direction = 'right') {
        $this->rotateTo($direction);
    }

    /**
     * @deprecated use setBackgroundColor() instead
     * @see PHPRtfLite/PHPRtfLite_Cell#setBackgroundColor()
     *
     * Sets background color to cell
     *
     * @param string $color background color
     */
    public function setBackGround($color) {
        $this->setBackgroundColor($color);
    }

    /**
     * @deprecated use setBorder() instead
     * @see PHPRtfLite/PHPRtfLite_Cell#setBorder()
     *
     * Sets border to cell
     *
     * @param PHPRtfLite_BorderFormat   $borderFormat
     * @param boolean                   $left           if false, left border is not set (default true)
     * @param boolean                   $top            if false, top border is not set (default true)
     * @param boolean                   $right          if false, right border is not set (default true)
     * @param boolean                   $bottom         if false, bottom border is not set (default true)
     */
    public function setBorders(PHPRtfLite_Border_Format $borderFormat,
                               $left = true, $top = true,
                               $right = true, $bottom = true)
    {
        $border = new PHPRtfLite_Border();
        $border->setBorders($borderFormat, $left, $top, $right, $bottom);
        
        $this->setBorder($border);
    }
}
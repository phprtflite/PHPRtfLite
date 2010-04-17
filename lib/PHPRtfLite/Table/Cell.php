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
class PHPRtfLite_Table_Cell extends PHPRtfLite_Container
{

    /**
     * constants for rotation directions
     */
    const ROTATE_RIGHT  = 'right';
    const ROTATE_LEFT   = 'left';

    /**
     * @var PHPRtfLite
     */
    protected $_rtf;

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
    protected $_alignment           = self::TEXT_ALIGN_LEFT;

    /**
     * vertical alignment
     * @var string
     */
    protected $_verticalAlignment   = self::VERTICAL_ALIGN_TOP;

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
    public function __construct(PHPRtfLite_Table $table, $rowIndex, $columnIndex)
    {
        $this->_table       = $table;
        $this->_rowIndex    = $rowIndex;
        $this->_columnIndex = $columnIndex;
        $this->_rtf         = $table->getRtf();
    }

    /**
     * gets rtf
     *
     * @return PHPRtfLite
     */
    public function getRtf()
    {
        return $this->_rtf;
    }

    /**
     * Nesting tables are not suported in current version.
     * @throws PHPRtfLite_Exception
     */
    public function addTable($alignment = PHPRtfLite_Table::ALIGN_LEFT)
    {
        throw new PHPRtfLite_Exception('Nested table are not supported in the current version!');
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
    public function setTextAlignment($alignment = self::TEXT_ALIGN_LEFT)
    {
        $this->_alignment = $alignment;
    }

    /**
     * Gets text alignment for cell.
     *
     * @return string
     */
    public function getTextAlignment()
    {
        return $this->_alignment;
    }

    /**
     * Sets font to a cell. The method PHPRtfLite_Table->writeToCell() overrides it with another Font.
     * 
     * @param   PHPRtfLite_Font $font
     */
    public function setFont(PHPRtfLite_Font $font)
    {
        $this->_rtf->registerFont($font);
        $this->_font = $font;
    }

    /**
     * Gets font of cell.
     * 
     * @return PHPRtfLite_Font
     */
    public function getFont()
    {
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
    public function setVerticalAlignment($verticalAlignment = self::VERTICAL_ALIGN_TOP)
    {
        $this->_verticalAlignment = $verticalAlignment;
    }

    /**
     * Gets vertical alignment of cell
     *
     * @return string
     */
    public function getVerticalAlignment()
    {
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
    public function rotateTo($rotateTo = self::ROTATE_RIGHT)
    {
        $this->_rotateTo = $rotateTo;
    }

    /**
     * Gets rotation direction of cell
     * 
     * @return string
     */
    public function getRotateTo()
    {
        return $this->_rotateTo;
    }

    /**
     * Sets background color
     *
     * @param string $color background color
     */
    public function setBackgroundColor($color)
    {
        $this->_backgroundColor = $color;
        $this->_rtf->getColorTable()->add($color);
    }

    /**
     * Gets background color
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->_backgroundColor;
    }

    /**
     * Sets that cell is horizontal merged
     *
     * @param boolean $merged
     */
    public function setHorizontalMerged($merged = true)
    {
        $this->_horizontalMerged = $merged;
    }

    /**
     * Returns true, if cell is horizontal merged
     *
     * @return boolean
     */
    public function isHorizontalMerged()
    {
        return $this->_horizontalMerged;
    }

    /**
     * Sets that cell is vertical merged
     *
     * @param boolean $merged
     */
    public function setVerticalMerged($merged = true)
    {
        $this->_verticalMerged = $merged;
    }

    /**
     * Returns true, if cell is horizontal merged
     *
     * @return boolean
     */
    public function isVerticalMerged()
    {
        return $this->_verticalMerged;
    }

    /**
     * Sets cell width
     *
     * @param float $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * Gets cell width
     *
     * @return float
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * gets border for specific cell
     * @param   integer     $rowIndex
     * @param   integer     $columnIndex
     * @return  PHPRtfLite_Border
     */
    protected function getBorderForCell($rowIndex, $columnIndex)
    {
        $cell = $this->_table->getCell($rowIndex, $columnIndex);
        $border = $cell->getBorder();
        if ($border === null) {
            $border = new PHPRtfLite_Border($this->_rtf);
            $cell->setCellBorder($border);
        }

        return $border;
    }

    /**
     * Sets border to a cell
     *
     * @param PHPRtfLite_Border $border
     */
    public function setBorder(PHPRtfLite_Border $border)
    {
        $borderFormatTop    = $border->getBorderTop();
        $borderFormatBottom = $border->getBorderBottom();
        $borderFormatLeft   = $border->getBorderLeft();
        $borderFormatRight  = $border->getBorderRight();

        if ($this->_border === null) {
            $this->_border = new PHPRtfLite_Border($this->_rtf);
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
            $this->getBorderForCell($this->_rowIndex - 1, $this->_columnIndex)->setBorderBottom($borderFormatTop);
        }

        if ($borderFormatBottom && $this->_table->checkIfCellExists($this->_rowIndex + 1, $this->_columnIndex)) {
            $this->getBorderForCell($this->_rowIndex + 1, $this->_columnIndex)->setBorderTop($borderFormatBottom);
        }

        if ($borderFormatLeft && $this->_table->checkIfCellExists($this->_rowIndex, $this->_columnIndex - 1)) {
            $this->getBorderForCell($this->_rowIndex, $this->_columnIndex - 1)->setBorderRight($borderFormatLeft);
        }

        if ($borderFormatRight && $this->_table->checkIfCellExists($this->_rowIndex, $this->_columnIndex + 1)) {
            $this->getBorderForCell($this->_rowIndex, $this->_columnIndex + 1)->setBorderLeft($borderFormatRight);
        }
    }

    /**
     * Sets cell border
     * 
     * @param PHPRtfLite_Border $border 
     */
    protected function setCellBorder(PHPRtfLite_Border $border)
    {
        $this->_border = $border;
    }

    /**
     * Gets cell border
     *
     * @return PHPRtfLite_Border
     */
    public function getBorder()
    {
        return $this->_border;
    }

    /**
     * Gets row index of cell
     *
     * @return integer
     */
    public function getRowIndex()
    {
        return $this->_rowIndex;
    }

    /**
     * Gets column index of cell
     *
     * @return integer
     */
    public function getColumnIndex()
    {
        return $this->_columnIndex;
    }

    /**
     * Gets rtf code for cell
     *
     * @return string rtf code
     */
    public function output()
    {
        $stream = $this->_rtf->getStream();
        $stream->write('{');

        switch ($this->_alignment) {
            case self::TEXT_ALIGN_LEFT:
                $stream->write('\ql');
                break;

            case self::TEXT_ALIGN_CENTER:
                $stream->write('\qc');
                break;

            case self::TEXT_ALIGN_RIGHT:
                $stream->write('\qr');
                break;

            case self::TEXT_ALIGN_JUSTIFY:
                $stream->write('\qj');
                break;
        }

        if ($this->_font) {
            $stream->write($this->_font->getContent());
        }

        parent::output();

        $stream->write('\cell \pard}' . "\r\n");
    }
}
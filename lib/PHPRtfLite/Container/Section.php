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
 * Class for creating sections within the rtf document.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Container
 */
class PHPRtfLite_Container_Section extends PHPRtfLite_Container {

    /**
     * border for section
     * @var PHPRtfLite_Border
     */
    protected $_border;

    /**
     * flag, if section is the first section within the rtf document
     * @var boolean
     */
    protected $_isFirst = false;

    /**
     * vertical alignment
     * @var string
     */
    protected $_verticalAlignment;

    /**
     * flag, if true, even and odd pages are using different layouts
     * @var boolean
     */
    protected $_useOddEvenDifferent = false;

    /**
     * number of columns within the section
     * @var integer
     */
    protected $_numberOfColumns = 1;

    /**
     * array of column widths. only used when using more than one column within the section
     * @var array
     */
    protected $_columnWidths;

    /**
     * flag for not breaking within the section, if true do not break
     * @var boolean
     */
    protected $_doNotBreak = false;

    /**
     * flag, if true using lines between the section columns
     * @var boolean
     */
    protected $_lineBetweenColumns = false;

    /**
     * defines space between the section columns
     * @var float
     */
    protected $_spaceBetweenColumns;

    /**
     * paper width
     * @var integer
     */
    protected $_paperWidth;

    /**
     * paper height
     * @var integer
     */
    protected $_paperHeight;

    /**
     * left margin
     * @var integer
     */
    protected $_marginLeft;

    /**
     * right margin
     * @var integer
     */
    protected $_marginRight;

    /**
     * top margin
     * @var integer
     */
    protected $_marginTop;

    /**
     * bottom margin
     * @var integer
     */
    protected $_marginBottom;

    /**
     * gutter
     * @var float
     */
    protected $_gutter;

    /**
     * flag, if true margins will be the opposite for odd and even pages
     * @var boolean
     */
    protected $_useMirrorMargins;

    /**
     * rtf headers
     * @var array
     */
    protected $_headers = array();

    /**
     * rtf footers
     * @var array
     */
    protected $_footers = array();


    /**
     * Sets the paper width of pages in section.
     * @param float $width paper width
     */
    public function setPaperWidth($width) {
        $this->_paperWidth = $width;
    }

    /**
     * Gets the paper width of pages in section.
     * @return float paper width
     */
    public function getPaperWidth() {
        return $this->_paperWidth;
    }

    /**
     * Sets the paper height of pages in section.   
     * @param float $height paper height
     */
    public function setPaperHeight($height) {
        $this->_paperHeight = $height;
    }

    /**
     * Gets the paper height of pages in section.
     * @return float paper height
     */
    public function getPaperHeight() {
        return $this->_paperHeight;
    }

    /**
     * gets if odd and even headers/footers are different
     *
     * @return boolean
     */
    public function isOddEvenDifferent() {
        return $this->_useOddEvenDifferent;
    }

    /**
     * Sets if odd and even headers/footers are different
     */
    public function setOddEvenDifferent($different = true) {
         $this->_useOddEvenDifferent = $different;
    }

    /**
     * Sets the margins of pages in section.
     * 
     * @param float $marginLeft Margin left
     * @param float $marginTop Margin top
     * @param float $marginRight Margin right
     * @param float $marginBottom Margin bottom
     */
    public function setMargins($marginLeft, $marginTop, $marginRight, $marginBottom) {
        $this->_marginLeft      = $marginLeft;
        $this->_marginTop       = $marginTop;
        $this->_marginRight     = $marginRight;
        $this->_marginBottom    = $marginBottom;
    }

    /**
     * Sets the left margin of document pages.
     * @param float $margin
     */
    public function setMarginLeft($margin) {
        $this->_marginLeft = $margin;
    }

    /**
     * Gets the left margin of document pages.
     * @return float $margin
     */
    public function getMarginLeft() {
        return $this->_marginLeft;
    }

    /**
     * Sets the right margin of document pages.
     * @param float $margin
     */
    public function setMarginRight($margin) {
        $this->_marginRight = $margin;
    }

    /**
     * Gets the right margin of document pages.
     * @return float $margin
     */
    public function getMarginRight() {
        return $this->_marginRight;
    }

    /**
     * Sets the top margin of document pages.
     * @param float $margin
     */
    public function setMarginTop($margin) {
        $this->_marginTop = $margin;
    }

    /**
     * Gets the top margin of document pages.
     * @return float $margin
     */
    public function getMarginTop() {
        return $this->_marginTop;
    }

    /**
     * Sets the bottom margin of document pages.
     * @param float $margin
     */
    public function setMarginBottom($margin) {
        $this->_marginBottom = $margin;
    }

    /**
     * Gets the bottom margin of document pages.
     * @return float $margin
     */
    public function getMarginBottom() {
        return $this->_marginBottom;
    }

    /**
     * Sets the gutter width. <br>   
     * NOTICE: Does note work with OpenOffice.
     * @param float $gutter Gutter width
     */
    public function setGutter($gutter) {
        $this->_gutter = $gutter;
    }

    /**
     * Gets the gutter width.
     * @return float $gutter gutter width
     */
    public function getGutter() {
        return $this->_gutter;
    }

    /**
     * Sets the margin definitions on left and right pages.<br>
     * Notice: Does not work with OpenOffice.
     */
    public function setMirrorMargins() {
        $this->_useMirrorMargins = true;
    }

    /**
     * Returns true, if use mirror margins should be used
     * @return boolean
     */
    public function isMirrorMargins() {
        return $this->_useMirrorMargins;
    }


    /**
     * Gets width of page layout.
     * @return float
     */
    public function getLayoutWidth() {
        $pageWidth      = !empty($this->_paperWidth)    ? $this->_paperWidth    : $this->_rtf->getPaperWidth();
        $marginLeft     = !empty($this->_marginLeft)    ? $this->_marginLeft    : $this->_rtf->getMarginLeft();
        $marginRight    = !empty($this->_marginRight)   ? $this->_marginRight   : $this->_rtf->getMarginRight();

        return $pageWidth - $marginLeft - $marginRight;
    }

    /**
     * Sets border to rtf document.
     * @param PHPRtfLite_Border $border
     */
    public function setBorder(PHPRtfLite_Border $border) {
        $this->_border = $border;
    }

    /**
     * Gets border of document.
     *
     * @return PHPRtfLite_Border
     */
    public function getBorder() {
        return $this->_border;
    }

    /**
     * Sets borders to rtf document.
     *
     * @param PHPRtfLite_Border_Format  $borderFormat
     * @param boolean                   $left
     * @param boolean                   $top
     * @param boolean                   $right
     * @param boolean                   $bottom
     */
    public function setBorders(PHPRtfLite_Border_Format $borderFormat,
                               $left = true, $top = true,
                               $right = true, $bottom = true)
    {
        if ($this->_border === null) {
            $this->_border = new PHPRtfLite_Border();
        }

        if ($left) {
            $this->_border->setBorderLeft($borderFormat);
        }

        if ($top) {
            $this->_border->setBorderTop($borderFormat);
        }

        if ($right) {
            $this->_border->setBorderRight($borderFormat);
        }

        if ($bottom) {
            $this->_border->setBorderBottom($borderFormat);
        }
    }

    /**
     * Sets number of columns in section.
     * @param integer $columnsCount number of columns
     */
    public function setNumberOfColumns($columnsCount) {
        $this->_numberOfColumns = $columnsCount;
        $this->_columnWidths = null;
    }

    /**
     * Gets number of columns in section.
     * @return  integer $columnsCount number of columns
     */
    public function getNumberOfColumns() {
        return $this->_numberOfColumns;
    }

    /**
     * Sets space (width) between columns.
     * @param float $spaceBetweenColumns Space between columns
     */
    public function setSpaceBetweenColumns($spaceBetweenColumns) {
        $this->_spaceBetweenColumns = $spaceBetweenColumns;
    }

    /**
     * Gets space (width) between columns.
     * @return float
     */
    public function getSpaceBetweenColumns() {
        return $this->_spaceBetweenColumns;
    }

    /**
     * Sets section columns with different widths.<br>
     * If you use this function, you shouldn't use {@see setNumberOfColumns}.
     * 
     * @param   array   $columnWidths array with columns widths
     *
     * @throws  PHPRtfLite_Exception, if column widths are exceeding the defined paper width
     */
    public function setColumnWidths($columnWidths) {
        if (is_array($columnWidths)) {
            $this->_numberOfColumns = count($columnWidths);
            $paperWidth = $this->_paperWidth ? $this->_paperWidth : $this->_rtf->getPaperWidth();
            $usedWidth = array_sum($columnWidths);
            
            if ($usedWidth <= $paperWidth) {
                $this->_columnWidths = $columnWidths;
            }
            else {
                throw new PHPRtfLite_Exception('The section column widths are exceeding the defined paper width!');
            }
        }
    }

    /**
     * Do not break within the section.
     * If foot notes are used in different sections, MS Word will always break sections.
     * @param boolean $doNotBreak
     */
    public function setNoBreak($doNotBreak = true) {
        $this->_doNotBreak = $doNotBreak;
    }

    /**
     * Sets line between columns.
     * @param boolean $flag
     */
    public function setLineBetweenColumns($flag = true) {
        $this->_lineBetweenColumns = $flag;
    }

    /**
     * Returns true, if line between columns is set.
     * @return boolean
     */
    public function hasLineBetweenColumns() {
        return $this->_lineBetweenColumns;
    }

    /**
     * Sets vertical alignment of text within the section.
     * @param string $alignment, represented by class constants VERTICAL_ALIGN_*<br>
     *   Possible values: <br>
     *     VERTICAL_ALIGN_TOP    = 'top';
     *     VERTICAL_ALIGN_BOTTOM = 'bottom';
     *     VERTICAL_ALIGN_CENTER = 'center';
     * 'top' => top (default)<br>
     * 'center' => center <br>
     * 'bottom' => bottom <br>
     * @todo bottom justify don't work
     */
    public function setVerticalAlignment($alignment) {
        $this->_verticalAlignment = $alignment;
    }

    /**
     * Gets vertical alignment of text within the section
     *
     * @return string
     */
    public function getVerticalAlignment() {
        return $this->_verticalAlignment;
    }


    /**
     * Creates header for sections.
     * @param string $type Represented by class constants PHPRtfLite_Container_Header::TYPE_*
     * Possible values: <br>
     *   PHPRtfLite_Container_Header::TYPE_ALL      => 'all' - all pages (different odd and even headers/footers must be not set) <br>
     *   PHPRtfLite_Container_Header::TYPE_LEFT     => 'left' - left pages (different odd and even headers/footers must be set) <br>
     *   PHPRtfLite_Container_Header::TYPE_RIGHT    => 'right' - right pages (different odd and even headers/footers must be set) <br>
     *   PHPRtfLite_Container_Header::TYPE_FIRST    => 'first' - first page
     *
     * @return PHPRtfLite_Container_Header
     */
    public function addHeader($type = PHPRtfLite_Container_Header::TYPE_ALL) {
        $header = new PHPRtfLite_Container_Header($this->_rtf, $type);
        $this->_headers[$type] = $header;
        
        return $header;
    }

    /**
     * Gets defined headers for document pages.
     * @return array contains PHPRtfLite_Container_Header objects
     */
    public function getHeaders() {
        return $this->_headers;
    }

    /**
     * Creates footer for the document.
     * @param string $type Represented by class constants PHPRtfLite_Container_Footer::TYPE_*
     *   PHPRtfLite_Container_Footer::TYPE_ALL      => 'all' - all pages (different odd and even headers/footers must be not set) <br>
     *   PHPRtfLite_Container_Footer::TYPE_LEFT     => 'left' - left pages (different odd and even headers/footers must be set) <br>
     *   PHPRtfLite_Container_Footer::TYPE_RIGHT    => 'right' - right pages (different odd and even headers/footers must be set)     <br>
     *   PHPRtfLite_Container_Footer::TYPE_FIRST    => 'first' - first page
     *
     * @return PHPRtfLite_Container_Footer
     */
    public function addFooter($type = PHPRtfLite_Container_Footer::TYPE_ALL) {
        $footer = new PHPRtfLite_Container_Footer($this->_rtf, $type);
        $this->_footers[$type] = $footer;

        return $footer;
    }

    /**
     * Gets defined footers for document pages.
     * @return array contains PHPRtfLite_Container_FOOTER objects
     */
    public function getFooters() {
        return $this->_footers;
    }

    /**
     * Breaks page.
     * @since 0.2.0/ This method is used instead of using "page" tag in PHPRTfLite_Container->writeText().
     */
    public function insertPageBreak() {
        $this->_elements[] = "\\page";
    }

    /**
     * Sets this section as first section
     * @param boolean $first
     */
    public function setFirst($first = true) {
        $this->_isFirst = $first;
    }

    /**
     * Returns true, if this section if the first section
     * @return boolean
     */
    public function isFirst() {
        return $this->_isFirst;
    }

    /**
     * Gets rtf code of section.
     * @return string rtf code
     */
    public function getContent() {
        $content = '';

        //section is not first section
        if (!$this->_isFirst) {
            $content .= '\sect \sectd ';
        }

        //headers
        $headers = $this->_headers ? $this->_headers : $this->_rtf->getHeaders();
        if (!empty($headers)) {
            foreach ($headers as $value) {
                $content .= $value->getContent();
            }
        }

        //footers
        $footers = $this->_footers ? $this->_footers : $this->_rtf->getFooters();
        if (!empty($footers)) {
            foreach ($footers as $value) {
                $content .= $value->getContent();
            }
        }

        //borders
        $border = $this->_border ? $this->_border : $this->_rtf->getBorder();
        if ($border) {
            $content .= $border->getContent($this->_rtf, '\pg');
        }

        //do not break within the section
        if ($this->_doNotBreak) {
            $content .= '\sbknone ';
        }

        //set column index, when using more than one column for this section
        if ($this->_numberOfColumns > 1) {
            $content .= '\cols' . $this->_numberOfColumns . ' ';
        }

        if ($this->_columnWidths === null) {
            if ($this->_spaceBetweenColumns) {
                  $content .= '\colsx' . round($this->_spaceBetweenColumns * PHPRtfLite::TWIPS_IN_CM) . ' ';
            }
        }
        else {
            $width = 0;
            foreach ($this->_columnWidths as $value) {
                $width += $value * PHPRtfLite::TWIPS_IN_CM;
            }

            $printableWidth = $this->_rtf->getPaperWidth() - $this->_rtf->getMarginLeft() - $this->_rtf->getMarginRight();
            $space = round(($printableWidth * PHPRtfLite::TWIPS_IN_CM - $width) / (count($this->_columnWidths) - 1));

            $i = 1;
            foreach ($this->_columnWidths as $key => $value) {
                $content .= '\colno' . $i . '\colw' . ($value * PHPRtfLite::TWIPS_IN_CM);
                if (!empty($this->_columnWidths[$key])) {
                    $content .= '\colsr' . $space;
                }
                $i++;
            }
            $content .= ' ';
        }

        if ($this->_lineBetweenColumns) {
            $content .= '\linebetcol ';
        }

        /*---Page part---*/
        if ($this->_paperWidth) {
            $content .= '\pgwsxn' . round($this->_paperWidth * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_paperHeight) {
            $content .= '\pghsxn' . round($this->_paperHeight * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_marginLeft) {
            $content .= '\marglsxn' . round($this->_marginLeft * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_marginRight) {
            $content .= '\margrsxn' . round($this->_marginRight * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_marginTop) {
            $content .= '\margtsxn' . round($this->_marginTop * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_marginBottom) {
            $content .= '\margbsxn' . round($this->_marginBottom * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_gutter) {
            $content .= '\guttersxn' . round($this->_gutter * PHPRtfLite::TWIPS_IN_CM) . ' ';
        }

        if ($this->_useMirrorMargins) {
            $content .= '\margmirsxn ';
        }

        if ($this->_verticalAlignment) {
            switch ($this->_verticalAlignment) {
                case 'center':
                    $content .= '\vertalc ';
                    break;

                case 'bottom':
                    $content .= '\vertalb ';
                    break;

                case 'justify':
                    $content .= '\vertalj ';
                    break;

                default:
                    $content .= '\vertalt ';
                    break;
            }
        }

        $content .= "\r\n" . parent::getContent() . "\r\n";

        return $content;
    }


    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setNumberOfColumns() instead
     * @see PHPRtfLite/PHPRtfLite_Container_Section#setNumberOfColumns()
     * Sets number of columns in section.
     * @param integer $columnsCount
     */
    public function setColumnsCount($columnsCount) {
        $this->setNumberOfColumns($columnsCount);
    }

    /**
     * @deprecated use setColumnWidths() instead
     * @see PHPRtfLite/PHPRtfLite_Container_Section#setColumnWidths()
     * Sets section columns with different widths.
     * @param array $columnWidths array with columns widths
     */
    public function setColumns($columnWidths) {
	$this->setColumnWidths($columnWidths);
    }

}
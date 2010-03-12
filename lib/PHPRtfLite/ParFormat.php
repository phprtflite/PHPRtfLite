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
 * Paragraph formatting class for rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_ParFormat {

    /**
     * constants for text align
     */
    const TEXT_ALIGN_LEFT   = 'left';
    const TEXT_ALIGN_CENTER = 'center';
    const TEXT_ALIGN_RIGHT  = 'right';
    const TEXT_ALIGN_JUSTIFY = 'justify';

    /**
     * text alignment
     * @var string
     */
    protected $_alignment;

    /**
     * indention of first line
     * @var float
     */
    protected $_indentFirstLine = 0;

    /**
     * left indention of paragraph
     * @var float
     */
    protected $_indentLeft = 0;

    /**
     * right indention of paragraph
     * @var float
     */
    protected $_indentRight = 0;

    /**
     * space before paragraph
     * @var float
     */
    protected $_spaceBefore = 0;

    /**
     * space after paragraph
     * @var float
     */
    protected $_spaceAfter = 0;

    /**
     * space between each line of paragraph
     * @var float
     */
    protected $_spaceBetweenLines = 0;

    /**
     * shading of paragraph
     * @var integer
     */
    protected $_shading = 0;

    /**
     * background color of paragraph
     * @var string
     */
    protected $_backgroundColor;

    /**
     * border instance
     * @var PHPRtfLite_Border
     */
    protected $_border;


    /**
     * Constructor
     * 
     * @param   string  $alignment  represented by class constants TEXT_ALIGN_*<br>
     *   Possible values:<br>
     *     TEXT_ALIGN_LEFT      => 'left'    - left alignment<br>
     *     TEXT_ALIGN_RIGHT     => 'right'   - right alignment<br>
     *     TEXT_ALIGN_CENTER    => 'center'  - center alignment<br>
     *     TEXT_ALIGN_JUSTIFY   => 'justify' - justify alignment
     */
    public function __construct($alignment = self::TEXT_ALIGN_LEFT) {
        $this->_alignment = $alignment;
    }

    /**
     * Sets text alignment
     *
     * @param   string  $alignment  represented by class constants TEXT_ALIGN_*<br>
     *   Possible values:<br>
     *     TEXT_ALIGN_LEFT      => 'left'    - left alignment<br>
     *     TEXT_ALIGN_RIGHT     => 'right'   - right alignment<br>
     *     TEXT_ALIGN_CENTER    => 'center'  - center alignment<br>
     *     TEXT_ALIGN_JUSTIFY   => 'justify' - justify alignment
     */
    public function setTextAlignment($alignment) {
        $this->_alignment = $alignment;
    }

    /**
     * Gets text alignment
     *
     * @return string
     */
    public function getTextAlignment() {
        return $this->_alignment;
    }

    /**
     * Sets first line indention (default 0)
     *
     * @param   float   $indentFirst
     */
    public function setIndentFirstLine($indentFirst) {
        $this->_indentFirstLine = round($indentFirst * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets first line indention
     * 
     * @return float
     */
    public function getIndentFirstLine() {
        return $this->_indentFirstLine;
    }

    /**
     * Sets left indent (default 0)
     *
     * @param   float   $indentLeft
     */
    public function setIndentLeft($indentLeft) {
        $this->_indentLeft = round($indentLeft * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets left indent
     *
     * @return float
     */
    public function getIndentLeft() {
        return $this->_indentLeft;
    }

    /**
     * Sets right indent (default 0)
     *
     * @param   float   $indentRight
     */
    public function setIndentRight($indentRight) {
        $this->_indentRight = round($indentRight * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets right indent
     *
     * @return float
     */
    public function getIndentRight() {
        return $this->_indentRight;
    }

    /**
     * Sets the space before paragraph
     *
     * @param   integer $spaceBefore space before
     */
    public function setSpaceBefore($spaceBefore) {
        $this->_spaceBefore = round($spaceBefore * PHPRtfLite::SPACE_IN_POINTS);
    }

    /**
     * Gets the space before paragraph
     *
     * @return  integer
     */
    public function getSpaceBefore() {
        return $this->_spaceBefore;
    }
    
    /**
     * Sets the space after paragraph
     *
     * @param integer $spaceAfter space after
     */
    public function setSpaceAfter($spaceAfter) {
        $this->_spaceAfter = round($spaceAfter * PHPRtfLite::SPACE_IN_POINTS);
    }

    /**
     * Gets the space after paragraph
     *
     * @return integer
     */
    public function getSpaceAfter() {
        return $this->_spaceAfter;
    }

    /**
     * Sets line space
     *
     * @param   integer     $spaceBetweenLines  space between lines
     */
    public function setSpaceBetweenLines($spaceBetweenLines) {
        $this->_spaceBetweenLines = round($spaceBetweenLines * PHPRtfLite::SPACE_IN_LINES);
    }

    /**
     * Gets line space
     *
     * @return  integer
     */
    public function getSpaceBetweenLines() {
        return $this->_spaceBetweenLines;
    }

    /**
     * Sets shading
     *
     * @param   integer $shading shading in percents (from 0 till 100)
     */
    public function setShading($shading) {
        $this->_shading = $shading * 100;
    }

    /**
     * Gets shading
     *
     * @return  integer
     */
    public function getShading() {
        return $this->_shading;
    }

    /**
     * Sets background color
     *
     * @param   string  $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
        $this->_backgroundColor = PHPRtfLite::convertHexColorToRtf($backgroundColor);
    }

    /**
     * Gets background color
     *
     * @return  string
     */
    public function getBackgroundColor() {
        return $this->_backgroundColor;
    }

    /**
     * Sets border of paragraph
     *
     * @param PHPRtfLite_Border $border
     */
    public function setBorder(PHPRtfLite_Border $border) {
        $this->_border = $border;
    }

    /**
     * Gets border of paragraph
     *
     * @return PHPRtfLite_Border
     */
    public function getBorder() {
        return $this->_border;
    }

    /**
     * Gets rtf code of paragraph
     *
     * @param   PHPRtfLite  $rtf
     *
     * @return  string  rtf code
     */
    public function getContent(PHPRtfLite $rtf) {
        $content = '';

        switch ($this->_alignment) {
            case self::TEXT_ALIGN_RIGHT:
                $content .= '\qr ';
                break;

            case self::TEXT_ALIGN_CENTER:
                $content .= '\qc ';
                break;

            case self::TEXT_ALIGN_JUSTIFY:
                $content .= '\qj ';
                break;

            default:
                $content .= '\ql ';
                break;
        }

        if ($this->_indentFirstLine > 0) {
            $content .= '\fi' . $this->_indentFirstLine.' ';
        }

        if ($this->_indentLeft > 0) {
            $content .= '\li' . $this->_indentLeft.' ';
        }

        if ($this->_indentRight > 0) {
            $content .= '\ri' . $this->_indentRight.' ';
        }

        if ($this->_spaceBefore > 0) {
            $content .= '\sb' . $this->_spaceBefore.' ';
        }

        if ($this->_spaceAfter > 0) {
            $content .= '\sa' . $this->_spaceAfter.' ';
        }

        if ($this->_spaceBetweenLines > 0) {
            $content .= '\sl' . $this->_spaceBetweenLines.' ';
        }

        if ($this->_border) {
            $content .= $this->_border->getContent($rtf, '\\');
        }

        if ($this->_shading > 0) {
            $content .= '\shading' . $this->_shading . ' ';
        }

        if ($this->_backgroundColor) {
            $rtf->addColor($this->_backgroundColor);
            $content .= '\cbpat' . $rtf->getColor($this->_backgroundColor) . ' ';
        }

        return $content;
    }


    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setIndentFirstLine() instead
     * @see PHPRtfLite/PHPRtfLite_ParFormat#setIndentFirstLine()
     *
     * Sets first line indent (default 0)
     * 
     * @param float $indentFirst
     */
    public function setIndentFirst($indentFirst) {
        $this->setIndentFirstLine($indentFirst);
    }

    /**
     * @deprecated use setBackgroundColor() instead
     * @see PHPRtfLite/PHPRtfLite_ParFormat#setBackgroundColor()
     *
     * Sets background color
     *
     * @param string $backgroundColor
     */
    public function setBackColor($backgroundColor) {
        $this->setBackgroundColor($backgroundColor);
    }

    /**
     * @deprecated use setBorder() instead
     * @see PHPRtfLite/PHPRtfLite_ParFormat#setBorder()
     *
     * Sets border
     *
     * @param PHPRtfLite_Border_Format  $borderFormat
     * @param boolean                   $left           if false, left border is not set (default true)
     * @param boolean                   $top            if false, top border is not set (default true)
     * @param boolean                   $right          if false, right border is not set (default true)
     * @param boolean                   $bottom         if false, bottom border is not set (default true)
     * @access public
     */
    public function setBorders(PHPRtfLite_Border_Format $borderFormat,
                               $left = true, $top = true,
                               $right = true, $bottom = true)
    {
        if (!$this->_border) {
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

}
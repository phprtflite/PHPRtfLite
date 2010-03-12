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
 * Class for fonts in rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Font {

    /**
     * constants for types of animated text
     */
    const ANIMATE_LAS_VEGAS_LIGHTS     = 1;
    const ANIMATE_BLINKING_BACKGROUND  = 2;
    const ANIMATE_SPARKLE_TEXT         = 3;
    const ANIMATE_MARCHING_BLACK_ANTS  = 4;
    const ANIMATE_MARCHING_RED_ANTS    = 5;
    const ANIMATE_SHIMMER              = 6;

    /**
     * font size
     * @var integer
     */
    protected $_size;

    /**
     * font family
     * @var string
     */
    protected $_fontFamily;

    /**
     * font color
     * @var string
     */
    protected $_color;

    /**
     * background color
     * @var string
     */
    protected $_backgroundColor;

    /**
     * true, if font is bold
     * @var boolean
     */
    protected $_isBold;

    /**
     * true, if font is italic
     * @var boolean
     */
    protected $_isItalic;

    /**
     * true, if font is underlined
     * @var boolean
     */
    protected $_isUnderlined;

    /**
     * true, if font is striked
     * @var boolean
     */
    protected $_isStriked;

    /**
     * true, if font is double striked
     * @var boolean
     */
    protected $_isDoubleStriked;

    /**
     * text animation
     * @var string
     */
    protected $_animation;
        
    /**
     * Constructor
     *
     * @param   integer $size               font size
     * @param   string  $fontFamily         font family (etc. "Times new Roman", "Arial" and other)
     * @param   string  $color              font color
     * @param   string  $backgroundColor    background color of font
     */
    public function __construct($size = 10, $fontFamily = '', $color = '', $backgroundColor = '') {
        $this->_size = $size;
        $this->_fontFamily = $fontFamily;

        if ($color) {
            $this->_color = PHPRtfLite::convertHexColorToRtf($color);
        }

        if ($backgroundColor) {
            $this->_backgroundColor = PHPRtfLite::convertHexColorToRtf($backgroundColor);
        }
    }

    /**
     * Sets text bold
     *
     * @param boolean $bold
     */
    public function setBold($bold = true) {
        $this->_isBold = $bold;
    }

    /**
     * Returns true, if text is styled bold
     *
     * @return boolean
     */
    public function isBold() {
        return $this->_isBold;
    }

    /**
     * Sets text italic
     *
     * @param boolean $italic
     */
    public function setItalic($italic = true) {
        $this->_isItalic = $italic;
    }

    /**
     * Returns true, if text is styled italic
     *
     * @return boolean
     */
    public function isItalic() {
        return $this->_isItalic;
    }

    /**
     * Sets text underline
     *
     * @param boolean $underlined
     */
    public function setUnderline($underlined = true) {
        $this->_isUnderlined = $underlined;
    }

    /**
     * Returns true, if text is styled underlined
     *
     * @return boolean
     */
    public function isUnderlined() {
        return $this->_isUnderlined;
    }

    /**
     * Sets striked text
     *
     * @param boolean $strike
     */
    public function setStriked($striked = true) {
        $this->_isStriked = $striked;

        if ($striked) {
            $this->_isDoubleStriked = false;
        }
    }

    /**
     * Returns true, if text is striked
     *
     * @return  boolean
     */
    public function isStriked() {
        return $this->_isStriked;
    }

    /**
     * Sets double striked text
     *
     * @param boolean $strike
     */
    public function setDoubleStriked($striked = true) {
        $this->_isDoubleStriked = $striked;

        if ($striked) {
            $this->_isStriked = false;
        }
    }

    /**
     * Returns true, if text is striked double
     *
     * @return boolean
     */
    public function isDoubleStriked($striked = true) {
        return $this->_isDoubleStriked;
    }

    /**
     * Sets animation for text
     *
     * @param integer $animation animation<br>
     *   Represented by class constants ANIMATE_*<br>
     *   Possible values:<br>
     *     ANIMATE_LAS_VEGAS_LIGHTS     => 1    - las vegas lights
     *     ANIMATE_BLINKING_BACKGROUND  => 2    - blinking brackground
     *     ANIMATE_SPARKLE_TEXT         => 3    - sparkle text
     *     ANIMATE_MARCHING_BLACK_ANTS  => 4    - marching black ants
     *     ANIMATE_MARCHING_RED_ANTS    => 5    - marching red ants
     *     ANIMATE_SHIMMER              => 6    - shimmer
     */ 
    public function setAnimation($animation) {
          $this->_animation = $animation;
    }

    /**
     * Gets rtf code of font
     *
     * @param  PHPRtfLite   $rtf
     *
     * @return string rtf code
     */
    public function getContent(PHPRtfLite $rtf) {
        $content = $this->_size > 0 ? '\fs' . ($this->_size * 2) . ' ' : '';

        if (!empty($this->_fontFamily)) {
            $rtf->addFont($this->_fontFamily);
            $content .= $rtf->getFont($this->_fontFamily).' ';
        }

        if ($this->_color) {
            $rtf->addColor($this->_color);
            $content .= $rtf->getFontColor($this->_color).' ';
        }

        if ($this->_backgroundColor) {
            $rtf->addColor($this->_backgroundColor);
            $content .= $rtf->getBackgroundColor($this->_backgroundColor).' ';
        }

        $content .= $this->_isBold          ? '\b '                             : '';
        $content .= $this->_isItalic        ? '\i '                             : '';
        $content .= $this->_isUnderlined    ? '\ul '                            : '';
        $content .= $this->_animation       ? '\animtext' . $this->_animation   : '';
        $content .= $this->_isStriked       ? '\strike ' . $this->_animation    : '';
        $content .= $this->_isDoubleStriked ? '\striked1 ' . $this->_animation  : '';

        return $content;
    }


    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setStriked() or setDoubleStriked() instead
     * @see PHPRtfLite/PHPRtfLite_Font#setStriked()
     * @see PHPRtfLite/PHPRtfLite_Font#setDoubleStriked()
     *
     * Sets striked text
     *
     * @param $strike If 1 then single, if 2 then double
     */
    public function setStrike($strike = 1) {
        switch ($strike) {
            case 0:
                $this->setStriked(false);
                $this->setDoubleStriked(false);
                break;
            case 1:
                $this->setStriked();
                break;
            case 2:
                $this->setDoubleStriked();
                break;
        }
    }

    /**
     * @deprecated use setAnimation() instead
     * @see PHPRtfLite/PHPRtfLite_Font#setAnimation()
     *
     * Sets animation to text
     *
     * @param integer $animatedText animated text<br>
     *   Possible values: <br>
     *     '1' => Las Vegas Lights,  <br>
     *     '2' => Blinking background, <br>
     *     '3' => Sparkle text, <br>
     *     '4' => Marching black ants, <br>
     *     '5' => Marching red ants, <br>
     *     '6' => Shimmer
     */
    public function setAnimatedText($animatedText) {
        $this->setAnimation($animation);
    }

}
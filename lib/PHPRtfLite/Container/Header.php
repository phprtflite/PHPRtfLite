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
 * Class for creating headers within the rtf document or section.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Container
 */
class PHPRtfLite_Container_Header extends PHPRtfLite_Container {

    /**
     * constants defining header types
     */
    const TYPE_ALL      = 'all';
    const TYPE_LEFT     = 'left';
    const TYPE_RIGHT    = 'right';
    const TYPE_FIRST    = 'first';

    /**
     * @var PHPRtfLite
     */
    protected $_rtf;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var float
     */
    protected $_offsetHeight;


    /**
     * Constructor
     *
     * @param PHPRtfLite    $rtf
     * @param string        $type
     */
    public function __construct(PHPRtfLite $rtf, $type) {
        $this->_rtf = $rtf;
        $this->_type = $type;

        if ($this->_type == self::TYPE_FIRST) {
            $rtf->setFirstPageHasSpecialLayout(true);
        }
    }

    /**
     * Set vertical header position from the top of the page
     *
     * @param   float   $height
     */
    public function setPosition($height) {
        $this->_offsetHeight = $height;
    }

    /**
     * Gets type as rtf code
     *
     * @return string rtf code
     * @throws PHPRtfLite_Exception, if type is not allowed,
     *   because of the rtf document specific settings.
     */
    protected function getTypeAsRtfCode() {
        switch ($this->_type) {
            case self::TYPE_ALL:
                if (!$this->_rtf->isOddEvenDifferent()) {
                    return 'header';
                }

                throw new PHPRtfLite_Exception('Header type ' . $this->_type . ' is not allowed, when using odd even different!');

            case self::TYPE_LEFT:
                if ($this->_rtf->isOddEvenDifferent()) {
                    return 'headerl';
                }

                throw new PHPRtfLite_Exception('Header type ' . $this->_type . ' is not allowed, when using not odd even different!');

            case self::TYPE_RIGHT:
                if ($this->_rtf->isOddEvenDifferent()) {
                    return 'headerr';
                }

                throw new PHPRtfLite_Exception('Header type ' . $this->_type . ' is not allowed, when using not odd even different!');

            case self::TYPE_FIRST:
                if ($this->_rtf->getFirstPageHasSpecialLayout()) {
                    return 'headerf';
                }

                throw new PHPRtfLite_Exception('Header type ' . $this->_type . ' is not allowed, when using not special layout for first page!');

            default:
                throw new PHPRtfLite_Exception('Header type is not defined! You gave me: ', $this->_type);
        }
    }

    /**
     * overwritten method, throws exception, because footers does not support footnotes/endnotes
     *
     * @param string                $noteText
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     *
     * @throws PHPRtfLite_Exception, footnote/endnote is not supported by footers
     */
    public function addFootnote($noteText, PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null) {
        throw new PHPRtfLite_Exception('Headers does not support footnotes!');
    }

    /**
     * overwritten method, throws exception, because footers does not support footnotes/endnotes
     *
     * @param string                $noteText
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     *
     * @throws PHPRtfLite_Exception, footnote/endnote is not supported by footers
     */
    public function addEndnote($noteText, PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null) {
        throw new PHPRtfLite_Exception('Headers does not support endnotes!');
    }

    /**
     * Gets rtf code of header
     * 
     * @return string rtf code
     */
    public function getContent() {
        $content = isset($this->_offsetHeight)
                   ? '\headery' . round(PHPRtfLite::TWIPS_IN_CM * $this->_offsetHeight) . ' '
                   : '';

        $content .= '{\\' . $this->getTypeAsRtfCode() . ' '
                 .    parent::getContent()
                 .    '\par '
                 .  '}';

        return $content . "\r\n";
    }

}
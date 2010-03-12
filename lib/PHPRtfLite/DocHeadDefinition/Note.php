<?php

/*
    PHPRtfLite
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
 * class for doucment head definition for footnotes and endnotes
 * @version     1.0.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010 Steffen Zeidler
 * @package     PHPRtfLite_DocDefinition
 */
class PHPRtfLite_DocHeadDefinition_Note
{

    /**
     * footnote type
     * @var integer
     */
    protected $_footnoteNumberingType   = PHPRtfLite_Note::NUMTYPE_ARABIC_NUMBERS;

    /**
     * endnote type
     * @var integer
     */
    protected $_endnoteNumberingType    = PHPRtfLite_Note::NUMTYPE_ROMAN_LC;

    /**
     * flag for restarting footnote numbering on each page
     * @var boolean
     */
    protected $_footnoteRestartEachPage = false;

    /**
     * flag for restarting endnote numbering on each page
     * @var boolean
     */
    protected $_endnoteRestartEachPage  = false;

    /**
     * start number for footnotes
     * @var integer
     */
    protected $_footnoteStartNumber     = 1;

    /**
     * start number for endnotes
     * @var integer
     */
    protected $_endnoteStartNumber      = 1;


    /**
     * sets footnote numbering type
     *
     * @param integer $numberingType
     */
    public function setFootnoteNumberingType($numberingType) {
        $this->_footnoteNumberingType = $numberingType;
    }

    /**
     * gets footnote numbering type
     *
     * @return integer
     */
    public function getFootnoteNumberingType() {
        return $this->_footnoteNumberingType;
    }

    /**
     * sets endnote numbering type
     *
     * @param integer $numberingType
     */
    public function setEndnoteNumberingType($numberingType) {
        $this->_endnoteNumberingType = $numberingType;
    }

    /**
     * gets endnote numbering type
     *
     * @return integer
     */
    public function getEndnoteNumberingType() {
        return $this->_endnoteNumberingType;
    }

    /**
     * sets footnote start number
     *
     * @param integer $startNumber
     */
    public function setFootnoteStartNumber($startNumber) {
        $this->_footnoteStartNumber = $startNumber;
    }

    /**
     * gets footnote start number
     *
     * @return integer
     */
    public function getFootnoteStartNumber() {
        return $this->_footnoteStartNumber;
    }

    /**
     * sets endnote start number
     *
     * @param integer $startNumber
     */
    public function setEndnoteStartNumber($startNumber) {
        $this->_endnoteStartNumber = $startNumber;
    }

    /**
     * gets endnote start number
     *
     * @return integer
     */
    public function getEndnoteStartNumber() {
        return $this->_endnoteStartNumber;
    }

    /**
     * sets restart footnote number on each page
     */
    public function setRestartFootnoteNumberEachPage() {
        $this->_footnoteRestartEachPage = true;
    }

    /**
     * checks, if footnote numbering shall be started on each page
     *
     * @return boolean
     */
    public function isRestartFootnoteNumberEachPage() {
        return $this->_endnoteRestartEachPage;
    }

    /**
     * sets restart endnote number on each page
     */
    public function setRestartEndnoteNumberEachPage() {
        $this->_endnoteRestartEachPage = true;
    }

    /**
     * checks, if endnote numbering shall be started on each page
     *
     * @return boolean
     */
    public function isRestartEndnoteNumberEachPage() {
        return $this->_endnoteRestartEachPage;
    }

    /**
     * renders document definition head for footnotes/endnotes
     *
     * @return string
     */
    public function getContent() {
        $content  = '';
        $content .= PHPRtfLite_Note::getFootnoteNumberingTypeAsRtf($this->_footnoteNumberingType) . ' ';
        $content .= PHPRtfLite_Note::getEndnoteNumberingTypeAsRtf($this->_endnoteNumberingType) . ' ';
        $content .= '\\ftnstart' . $this->_footnoteStartNumber . ' ';
        $content .= '\\aftnstart' . $this->_endnoteStartNumber . ' ';

        if ($this->_footnoteRestartEachPage) {
            $content .= '\\ftnrstpg ';
        }
        if ($this->_endnoteRestartEachPage) {
            $content .= '\\aftnrstpg ';
        }

        return $content;
    }

}
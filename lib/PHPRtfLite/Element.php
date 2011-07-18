<?php
/*
    PHPRtfLite
    Copyright 2010-2011 Steffen Zeidler <sigma_z@web.de>

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
 * class for creating elements used in containers like sections, footers and headers.
 * @version     1.1.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010-2011 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Element
 */
class PHPRtfLite_Element
{

    /**
     * @var string
     */
    protected $_text                = '';
    /**
     * @var boolean
     */
    protected $_isTextRtfCode       = false;
    /**
     * @var boolean
     */
    protected $_convertTagsToRtf    = false;
    /**
     * @var PHPRtfLite_Font
     */
    protected $_font;
    /**
     * @var PHPRtfLite_ParFormat
     */
    protected $_parFormat;


    /**
     * constructor
     *
     * @param PHPRtfLite            $rtf
     * @param string                $text
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     */
    public function __construct(PHPRtfLite $rtf,
                                $text = '',
                                PHPRtfLite_Font $font = null,
                                PHPRtfLite_ParFormat $parFormat = null)
    {
        if ($font) {
            $rtf->registerFont($font);
        }
        if ($parFormat) {
            $rtf->registerParFormat($parFormat);
        }

        $this->_rtf         = $rtf;
        $this->_text        = $text;
        $this->_font        = $font;
        $this->_parFormat   = $parFormat;
    }


    /**
     * checks, if element is an empty paragraph
     *
     * @return boolean
     */
    public function isEmptyParagraph()
    {
        return ($this->_parFormat && $this->_text == '');
    }


    /**
     * sets flag, that text tags shall be converted into rtf code
     */
    public function setConvertTagsToRtf()
    {
        $this->_convertTagsToRtf = true;
    }


    /**
     * gets par format
     *
     * @return PHPRtfLite_ParFormat
     */
    public function getParFormat()
    {
        return $this->_parFormat;
    }


    /**
     * converts text tags into rtf code
     *
     * @param  string $text
     * @return string
     */
    public static function convertTagsToRtf($text)
    {
        $search = array(
            // bold
            '/<STRONG[ ]*>(.*?)<\/STRONG[ ]*>/smi',
            '/<B[ ]*>(.*?)<\/B[ ]*>/smi',
            // italic
            '/<EM[ ]*>(.*?)<\/EM[ ]*>/smi',
            '/<I[ ]*>(.*?)<\/I[ ]*>/smi',
            // underline
            '/<U[ ]*>(.*?)<\/U[ ]*>/smi',
            // break
            '/<BR[ ]*(\/)?[ ]*>/smi',
            '/<LINE[ ]*(\/)?[ ]*>/smi',
            // horizontal rule
            '/<HR[ ]*(\/)?[ ]*>/smi',
            '/<CHDATE[ ]*(\/)?[ ]*>/smi',
            '/<CHDPL[ ]*(\/)?[ ]*>/smi',
            '/<CHDPA[ ]*(\/)?[ ]*>/smi',
            '/<CHTIME[ ]*(\/)?[ ]*>/smi',
            '/<CHPGN[ ]*(\/)?[ ]*>/smi',
            // tab
            '/<TAB[ ]*(\/)?[ ]*>/smi',
            // bullet
            '/<BULLET[ ]*(\/)?[ ]*>/smi',
            '/<PAGENUM[ ]*(\/)?[ ]*>/smi',
            '/<SECTNUM[ ]*(\/)?[ ]*>/smi',
//            '/<PAGE[ ]*(\/)?[ ]*>/smi',
//            '/<SECT[ ]*(\/)?[ ]*>/smi'
        );

        $replace = array(
            // bold
            '\b \1\b0 ',
            '\b \1\b0 ',
            // italic
            '\i \1\i0 ',
            '\i \1\i0 ',
            // underline
            '\ul \1\ul0 ',
            // break
            '\line ',
            '\line ',
            // horizontal rule
            '{\pard \brdrb \brdrs \brdrw10 \brsp20 \par}',
            '\chdate ',
            '\chdpl ',
            '\chdpa ',
            '\chtime ',
            '\chpgn ',
            // tab
            '\tab ',
            // bullet
            '\bullet ',
            '\chpgn ',
            '\sectnum ',
//            '\page ',
//            '\sect '
        );

        $text = preg_replace($search, $replace, $text);

        return $text;
    }


    /**
     * sets rtf code directly for this element without extra converting
     *
     * @param string $text
     */
    public function writeRtfCode($text)
    {
        $this->_text = $text;
        $this->_isTextRtfCode = true;
    }


    /**
     * gets opening token
     *
     * @return string
     */
    protected function getOpeningToken()
    {
        return '{';
    }


    /**
     * gets closing token
     *
     * @return string
     */
    protected function getClosingToken()
    {
        return '}';
    }


    /**
     * renders the element
     */
    public function render()
    {
        $stream = $this->_rtf->getStream();
        $text = $this->_text;

        if (!$this->_isTextRtfCode) {
            $text = PHPRtfLite::quoteRtfCode($text);
            if ($this->_convertTagsToRtf) {
                $text = self::convertTagsToRtf($text);
            }
            $text = PHPRtfLite_Utf8::getUnicodeEntities($text, $this->_rtf->getCharset());
        }

        $stream->write($this->getOpeningToken());

        if ($this->_font) {
            $stream->write($this->_font->getContent());
        }
        if ($this->isEmptyParagraph()) {
            $stream->write('\par');
        }
        else if ($this->_font && empty($text)) {
            $stream->write('\~');
        }
        else {
            $stream->write($text);
        }

        $stream->write($this->getClosingToken() . "\r\n");
    }

}
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
 * Abstract class for creating containers like sections, footers and headers.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Container
 */
abstract class PHPRtfLite_Container {

    /**
     * constants for text alignment
     */
    const TEXT_ALIGN_LEFT       = 'left';
    const TEXT_ALIGN_RIGHT      = 'right';
    const TEXT_ALIGN_CENTER     = 'center';
    const TEXT_ALIGN_JUSTIFY    = 'justify';

    /**
     * constants for vertical alignment
     */
    const VERTICAL_ALIGN_TOP    = 'top';
    const VERTICAL_ALIGN_BOTTOM = 'bottom';
    const VERTICAL_ALIGN_CENTER = 'center';

    /**
     * @var PHPRtfLite
     */
    protected $_rtf;

    /**
     * @var array
     */
    protected $_elements = array();

    /**
     * @var string
     */
    protected $_pard = '\pard ';

    /**
     * flag, if true the paragraph is empty
     * @var boolean
     */
    protected $_emptyPar = false;

    /**
     * flag, true if a container contains notes
     * @var boolean
     */
    protected $_hasNotes = false;


    /**
     * Constructor
     * @param PHPRtfLite $rtf
     */
    public function __construct(PHPRtfLite $rtf) {
        $this->_rtf = $rtf;
    }

    /**
     * Gets rtf object
     * @return PHPRtfLite
     */
    public function getRtf() {
        return $this->_rtf;
    }

    /**
     * Writes rtf code directly to rtf container.
     * @param string $text
     */
    public function writeRtfCode($text) {
        $this->_elements[] = $text;
    }

    /**
     * Adds empty paragraph to container.
     * @param PHPRtfLite_Font       $font       font of text.
     * @param PHPRtfLite_ParFormat  $parFormat  paragraph format.
     */
    public function emptyParagraph(PHPRtfLite_Font $font, PHPRtfLite_ParFormat $parFormat) {
        if ($parFormat && $font) {
            $content  = (count($this->_elements) != 0 && !$this->_emptyPar)
                        ? '\par '
                        : '';
                        
            $content .= $this->_pard . $parFormat->getContent($this->_rtf);
            $content .= '{' . $font->getContent($this->_rtf) .' \par}' . "\r\n";

            $this->_elements[] = $content;
            $this->_emptyPar = true;
        }
    }

    /**
     * Writes text to container.
     * 
     * @param string $text Text. Also you can use html style tags. Possible tags:<br>
     *   strong, b- bold; <br>
     *   em - ; <br>
     *   i - italic; <br>
     *   u - underline; <br>
     *   br - line break; <br>
     *   chdate - current date; <br>
     *   chdpl - current date in long format; <br>
     *   chdpa - current date in abbreviated format; <br>
     *   chtime - current time; <br>
     *   chpgn, pagenum - page number ; <br>
     *   tab - tab
     *   sectnum - section number; <br>
     *   line - line break; <br>
     *   page - page break; <br>
     *   sect - section break; <br>
     * @param PHPRtfLite_Font       $font           font of text
     * @param PHPRtfLite_ParFormat  $parFormat      paragraph format, if null, text is written in the same paragraph.
     * @param boolean               $replaceTags    if false, then html style tags are not replaced with rtf code
     * @todo Documentation
     */
    public function writeText($text,
                              PHPRtfLite_Font $font = null,
                              PHPRtfLite_ParFormat $parFormat = null,
                              $replaceTags = true)
    {
        $text = PHPRtfLite::quoteRtfCode($text);

        if ($replaceTags) {
            //bold
            $text = preg_replace('/<STRONG[ ]*>(.*?)<\/STRONG[ ]*>/smi', '\\b \\1\\b0 ', $text);
            $text = preg_replace('/<B[ ]*>(.*?)<\/B[ ]*>/smi', '\\b \\1\\b0 ', $text);
            //italic
            $text = preg_replace('/<EM[ ]*>(.*?)<\/EM[ ]*>/smi', '\\i \\1\\i0 ', $text);
            $text = preg_replace('/<I[ ]*>(.*?)<\/I[ ]*>/smi', '\\i \\1\\i0 ', $text);
            //underline
            $text = preg_replace('/<U[ ]*>(.*?)<\/U[ ]*>/smi', '\\ul \\1\\ul0 ', $text);
            //break
            $text = preg_replace('/<BR[ ]*(\/)?[ ]*>/smi', '\\line ', $text);
            //horizontal rule
            $text = preg_replace('/<HR[ ]*(\/)?[ ]*>/smi', '{\pard \brdrb \brdrs \brdrw10 \brsp20 \par}', $text);

            $text = preg_replace('/<CHDATE[ ]*(\/)?[ ]*>/smi', '\\chdate ', $text);
            $text = preg_replace('/<CHDPL[ ]*(\/)?[ ]*>/smi', '\\\chdpl ', $text);
            $text = preg_replace('/<CHDPA[ ]*(\/)?[ ]*>/smi', '\\chdpa ', $text);
            $text = preg_replace('/<CHTIME[ ]*(\/)?[ ]*>/smi', '\\chtime ', $text);
            $text = preg_replace('/<CHPGN[ ]*(\/)?[ ]*>/smi', '\\chpgn ', $text);

            $text = preg_replace('/<TAB[ ]*(\/)?[ ]*>/smi', '\\tab ', $text);
            $text = preg_replace('/<BULLET[ ]*(\/)?[ ]*>/smi', '\\bullet ', $text);

            $text = preg_replace('/<PAGENUM[ ]*(\/)?[ ]*>/smi', '\\chpgn ', $text);
            $text = preg_replace('/<SECTNUM[ ]*(\/)?[ ]*>/smi', '\\sectnum ', $text);

            $text = preg_replace('/<LINE[ ]*(\/)?[ ]*>/smi', '\\line ', $text);
            //$text = preg_replace('/<PAGE[ ]*(\/)?[ ]*>/smi', '\\page ', $text);
            //$text = preg_replace('/<SECT[ ]*(\/)?[ ]*>/smi', '\\sect', $text);
        }

        $text = PHPRtfLite_Utf8::getUnicodeEntities($text);

        //content formating
        $content  = ($parFormat && count($this->_elements) != 0 && !$this->_emptyPar)
                    ? '\par '
                    : '';

        $this->_emptyPar = false;
        
        $content .= $parFormat
                    ? $this->_pard . $parFormat->getContent($this->_rtf)
                    : '';
                    
        $content .= '{';

        if ($font) {
            $content .= $font->getContent($this->_rtf);
        }
        
        $content .= $text . '}' . "\r\n";

        $this->_elements[] = $content;
    }

    /**
     * Writes hyperlink to container.
     *
     * @param string                $hyperlink  hyperlink url (etc. "http://www.phprtf.com")
     * @param string                $text       hyperlinks text, if empty, hyperlink is written in previous paragraph format.
     * @param PHPRtfLite_Font       $font       font
     * @param PHPRtfLite_ParFormat  $parFormat  paragraph format, if null hyperlink is written in the same paragraph
     */
    public function writeHyperLink($hyperlink, $text, PHPRtfLite_Font $font, PHPRtfLite_ParFormat $parFormat = null) {
        $content = ($parFormat && count($this->_elements) != 0 && !$this->_emptyPar)
                   ? '\par '
                   : '';

        $content .= $parFormat ? $this->_pard . $parFormat->getContent($this->_rtf) : '';

        $this->_emptyPar = false;

        $this->_elements[] = $content . '{\field {\*\fldinst {HYPERLINK "' . $hyperlink . '"}}{\\fldrslt {';

        $this->writeText($text, $font, null);

        $this->_elements[] .= '}}}'."\r\n";
    }

    /**
     * Adds table to element container.
     *
     * @param  string $alignment Alingment of table. Represented by class constants TEXT_ALIGN_*<br>
     *    Possible values:<br>
     *      PHPRtfLite_Container::TEXT_ALIGN_LEFT   => 'left',<br>
     *      PHPRtfLite_Container::TEXT_ALIGN_CENTER => 'center',<br>
     *      PHPRtfLite_Container::TEXT_ALIGN_RIGHT  => 'right'<br>
     * 
     * @return PHPRtfLite_Table
     */
    public function addTable($alignment = self::TEXT_ALIGN_LEFT) {
        $this->_emptyPar = false;
        $table = new PHPRtfLite_Table($this, $alignment);
        $this->_elements[] = $table;
        
        return $table;
    }

    /**
     * Adds image to element container.
     * 
     * @param string                $fileName   name of image file.
     * @param PHPRtfLite_ParFormat  $parFormat  paragraph format, ff null image will appear in the same paragraph.
     * @param float                 $width      if null image is displayed by it's height.
     * @param float                 $height     if null image is displayed by it's width.
     *   If boths parameters are null, image is displayed as it is.
     *
     * @return PHPRtfLite_Image
     */
    public function addImage($fileName, PHPRtfLite_ParFormat $parFormat = null, $width = null, $height = null) {
        $this->_emptyPar = false;
        $image = new PHPRtfLite_Image($this->_rtf, $fileName, $parFormat, $width, $height);
        $this->_elements[] = $image;
        
        return $image;
    }

    /**
     * adds a footnote
     *
     * @param string                $noteText
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     * 
     * @return PHPRtfLite_Note
     */
    public function addFootnote($noteText, PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null) {
        $footnote = new PHPRtfLite_Note($this->_rtf, $noteText, $font, $parFormat);
        $this->_elements[] = $footnote;
        return $footnote;
    }

    /**
     * adds an endnote
     *
     * @param string                $noteText
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     *
     * @return PHPRtfLite_Note
     */
    public function addEndnote($noteText, PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null) {
        $endnote = new PHPRtfLite_Note($this->_rtf, $noteText, $font, $parFormat);
        $endnote->setIsFootnote(false);
        $this->_elements[] = $endnote;
        return $endnote;
    }

    /**
     * Gets rtf code of rtf container.
     * @return string rtf code
     */
    public function getContent() {
        $content = '';

        foreach($this->_elements as $key => $value) {
            if (is_string($value)) {
                $content .= $value;
            }
            else {
                if ($key != 0
                    && $value instanceof PHPRtfLite_Table
                    && !($this->_elements[$key - 1] instanceof PHPRtfLite_Table))
                {
                    $content .= '\par';
                }
                elseif ($value instanceof PHPRtfLite_Image) {
                    $parFormat = $value->getParFormat();
                    if ($parFormat instanceof PHPRtfLite_ParFormat) {
                        $content .= $key != 0 ? '\par' : '';
                        $content .= $this->_pard . $parFormat->getContent($this->_rtf);
                    }
                }

                $content .= $value->getContent();
            }
        }

        return $content;
    }

}
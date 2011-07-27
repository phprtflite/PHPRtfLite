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
 * Base class for rtf containers.
 * @version     1.1.1
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010-2011 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Container
 */
abstract class PHPRtfLite_Container_Base
{

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
     * constructor
     *
     * @param PHPRtfLite $rtf
     */
    public function __construct(PHPRtfLite $rtf)
    {
        $this->_rtf = $rtf;
    }


    /**
     * gets rtf object
     *
     * @return PHPRtfLite
     */
    public function getRtf()
    {
        return $this->_rtf;
    }


    /**
     * counts container elements
     *
     * @return integer
     */
    public function countElements()
    {
        return count($this->_elements);
    }


    /**
     * gets container elements
     *
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }


    /**
     * adds element with rtf code directly (no converting will be made by PHPRtfLite)
     *
     * @param string $text
     */
    public function writeRtfCode($text)
    {
        $element = new PHPRtfLite_Element($this->_rtf);
        $element->writeRtfCode($text);
        $this->_elements[] = $element;
    }


    /**
     * adds empty paragraph to container.
     *
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     */
    public function addEmptyParagraph(PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null)
    {
        if ($parFormat === null) {
            $parFormat = new PHPRtfLite_ParFormat();
        }
        $element = new PHPRtfLite_Element($this->_rtf, '', $font, $parFormat);
        $this->_elements[] = $element;
    }


    /**
     * writes text to container.
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
     * @param PHPRtfLite_Font       $font               font of text
     * @param PHPRtfLite_ParFormat  $parFormat          paragraph format, if null, text is written in the same paragraph.
     * @param boolean               $convertTagsToRtf   if false, then html style tags are not replaced with rtf code
     */
    public function writeText($text,
                              PHPRtfLite_Font $font = null,
                              PHPRtfLite_ParFormat $parFormat = null,
                              $convertTagsToRtf = true)
    {
        $element = new PHPRtfLite_Element($this->_rtf, $text, $font, $parFormat);
        if ($convertTagsToRtf) {
            $element->setConvertTagsToRtf();
        }
        $this->_elements[] = $element;
    }


    /**
     * writes hyperlink to container.
     *
     * @param string                $hyperlink          hyperlink url (etc. "http://www.phprtf.com")
     * @param string                $text               hyperlink text, if empty, hyperlink is written in previous paragraph format.
     * @param PHPRtfLite_Font       $font
     * @param PHPRtfLite_ParFormat  $parFormat
     * @param boolean               $convertTagsToRtf   if false, then html style tags are not replaced with rtf code
     */
    public function writeHyperLink($hyperlink,
                                   $text,
                                   PHPRtfLite_Font $font = null,
                                   PHPRtfLite_ParFormat $parFormat = null,
                                   $convertTagsToRtf = true)
    {
        $element = new PHPRtfLite_Element_Hyperlink($this->_rtf, $text, $font, $parFormat);
        $element->setHyperlink($hyperlink);
        if ($convertTagsToRtf) {
            $element->setConvertTagsToRtf();
        }
        $this->_elements[] = $element;
    }


    /**
     * adds table to element container.
     *
     * @param  string $alignment Alingment of table. Represented by class constants PHPRtfLite_Table::ALIGN_*<br>
     *    Possible values:<br>
     *      PHPRtfLite_Table::ALIGN_LEFT   => 'left',<br>
     *      PHPRtfLite_Table::ALIGN_CENTER => 'center',<br>
     *      PHPRtfLite_Table::ALIGN_RIGHT  => 'right'<br>
     *
     * @return PHPRtfLite_Table
     */
    public function addTable($alignment = PHPRtfLite_Table::ALIGN_LEFT)
    {
        $table = new PHPRtfLite_Table($this, $alignment);
        $this->_elements[] = $table;

        return $table;
    }


    /**
     * adds image to element container.
     *
     * @param string                $fileName   name of image file.
     * @param PHPRtfLite_ParFormat  $parFormat  paragraph format, ff null image will appear in the same paragraph.
     * @param float                 $width      if null image is displayed by it's height.
     * @param float                 $height     if null image is displayed by it's width.
     *   If boths parameters are null, image is displayed as it is.
     *
     * @return PHPRtfLite_Image
     */
    public function addImage($fileName, PHPRtfLite_ParFormat $parFormat = null, $width = null, $height = null)
    {
        $image = new PHPRtfLite_Image($this->_rtf, $fileName, $parFormat, $width, $height);
        $this->_elements[] = $image;

        return $image;
    }


    /**
     * renders rtf code for that container
     *
     * @return string rtf code
     */
    public function render()
    {
        $stream = $this->_rtf->getStream();

        if ($this instanceof PHPRtfLite_Table_Cell && $this->countElements() == 0) {
            $stream->write('{');
            $font = $this->getCellFont($this);
            if ($font) {
                $stream->write($font->getContent());
            }
            $stream->write('{\~}}');
        }

        $lastKey = $this->countElements() - 1;

        foreach ($this->_elements as $key => $element) {
            if ($this instanceof PHPRtfLite_Table_Cell && !($element instanceof PHPRtfLite_Table)) {
                // table cell initialization
                $stream->write('\pard\intbl\itap' . $this->getTable()->getNestDepth() . "\r\n");
                $stream->write($this->getCellAlignment());
            }

            $parFormat = null;
            if (!($element instanceof PHPRtfLite_Table)) {
                $parFormat = $element->getParFormat();
            }

            if ($parFormat) {
                $stream->write($this->_pard);
                if ($this instanceof PHPRtfLite_Table_Cell && $lastKey != $key) {
                    $stream->write('{');
                }
                $stream->write($parFormat->getContent());
            }

            $font = $this->getCellFont($element);
            if ($font) {
                $stream->write($font->getContent());
            }

            $element->render();

            if ($this->needToAddParagraph($key)) {
                $stream->write('\par ');
            }

            if ($font) {
                $stream->write($font->getClosingContent());
            }

            if ($parFormat && $this instanceof PHPRtfLite_Table_Cell && $lastKey != $key) {
                $stream->write('}');
            }
        }
    }


    /**
     * checks, if a \par has to be added
     *
     * @param   integer $key
     * @return  boolean
     */
    private function needToAddParagraph($key)
    {
        if (isset($this->_elements[$key + 1])) {
            $nextElement = $this->_elements[$key + 1];
            $element = $this->_elements[$key];
            $isNextElementTable = $nextElement instanceof PHPRtfLite_Table;

            if ($element instanceof PHPRtfLite_Table && $element->getNestDepth() == 1) {
                return !$element->getPreventEmptyParagraph();
            }
            else if ($element instanceof PHPRtfLite_Element) {
                return (!$element->isEmptyParagraph() && ($isNextElementTable || $nextElement->getParFormat()));
            }
            else if ($element instanceof PHPRtfLite_Image) {
                return ($isNextElementTable || $nextElement->getParFormat());
            }
        }

        return false;
    }


    /**
     * gets font if container is a cell
     *
     * @param   PHPRtfLite_Table    $element
     * @return  PHPRtfLite_Font     $font
     */
    private function getCellFont($element)
    {
        if ($this instanceof PHPRtfLite_Table_Cell && !($element instanceof PHPRtfLite_Table_Nested)) {
            return $this->getFont();
        }
        return false;
    }

}
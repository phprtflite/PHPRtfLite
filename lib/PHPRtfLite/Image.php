<?php
/* 
    PHPRtfLite
    Copyright 2007-2008 Denis Slaveckij <info@phprtf.com>
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
 * Class for displaying images in rtf documents.
 * @version     1.1.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010-2011 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Image
{

    /**
     * @var PHPRtfLite
     */
    protected $_rtf;

    /**
     * @var PHPRtfLite_ParFormat
     */
    protected $_parFormat;

    /**
     * @var string
     */
    protected $_file;

    /**
     * @var float
     */
    protected $_defaultWidth;

    /**
     * @var float
     */
    protected $_defaultHeight;

    /**
     * @var float
     */
    protected $_height;

    /**
     * @var float
     */
    protected $_width;

    /**
     * @var PHPRtfLite_Border
     */
    protected $_border;

    
    /**
     * constructor
     * 
     * @param PHPRtfLite    $rtf
     * @param string        $imageFile image file incl. path
     * @param float         $width
     * @param flaot         $height
     */
    public function __construct(PHPRtfLite $rtf,
                                $imageFile,
                                PHPRtfLite_ParFormat $parFormat = null,
                                $width = null,
                                $height = null)
    {
        $this->_rtf = $rtf;
        $this->_parFormat = $parFormat;
        if ($parFormat) {
            $parFormat->setColorTable($this->_rtf->getColorTable());
        }

        if (file_exists($imageFile)) {
            $this->_file = $imageFile;

            list($this->_defaultWidth, $this->_defaultHeight) = getimagesize($imageFile);
            
            $this->_width = $width;
            $this->_height = $height;
        }
        else {
            $this->_defaultWidth = 20;
            $this->_defaultHeight = 20;
        }
    }


    /**
     * checks, if image file is available or missing
     *
     * @return boolean
     */
    public function isMissing()
    {
        return $this->_file === null;
    }


    /**
     * sets paragraph format for image
     *
     * @param PHPRtfLite_ParFormat $parFormat
     */
    public function setParFormat(PHPRtfLite_ParFormat $parFormat)
    {
        $this->_parFormat = $parFormat;
        $parFormat->setColorTable($this->_rtf->getColorTable());
    }


    /**
     * gets paragraph format for image
     * 
     * @return PHPRtfLite_ParFormat
     */
    public function getParFormat()
    {
        return $this->_parFormat;
    }


    /**
     * sets image width
     *
     * @param   float   $width  if not defined image is displayed by it's height.
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }


    /**
     * gets image width
     * 
     * @return float
     */
    public function getWidth()
    {
        return $this->_width;
    }


    /**
     * sets image height
     *
     * @param   float   $height if not defined image is displayed by it's width.
     */
    public function setHeight($height)
    {
        $this->_height = $height;
    }


    /**
     * gets image height
     * 
     * @return float
     */
    public function getHeight()
    {
        return $this->_height;
    }


    /**
     * sets border of paragraph
     *
     * @param PHPRtfLite_Border $border
     */
    public function setBorder(PHPRtfLite_Border $border)
    {
        $this->_border = $border;
    }


    /**
     * gets border of paragraph
     *
     * @return PHPRtfLite_Border
     */
    public function getBorder()
    {
        return $this->_border;
    }


    /**
     * gets rtf image width
     * 
     * @return integer
     */
    private function getImageWidth()
    {
        if ($this->_width > 0) {
            return PHPRtfLite_Unit::getUnitInTwips($this->_width);
        }
        else if ($this->_height > 0) {
            $width = ($this->_defaultWidth / $this->_defaultHeight) * $this->_height;
            return PHPRtfLite_Unit::getUnitInTwips($width);
        }

        return PHPRtfLite_Unit::getPointsInTwips($this->_defaultWidth);
    }


    /**
     * gets rtf image height
     *
     * @return integer
     */
    private function getImageHeight()
    {
        if ($this->_height > 0) {
            return PHPRtfLite_Unit::getUnitInTwips($this->_height);
        }
        else if ($this->_width > 0) {
            $height = ($this->_defaultHeight /$this->_defaultWidth) * $this->_width;
            return PHPRtfLite_Unit::getUnitInTwips($height);
        }

        return PHPRtfLite_Unit::getPointsInTwips($this->_defaultHeight);
    }


    /**
     * adds rtf image code to show that the file is missing
     */
    private function addMissingFileToStream()
    {
        $stream = $this->_rtf->getStream();
        $stream->write('89504e470d0a1a0a0000000d494844520000001400000014080200000002eb8a5a00000001735247');
        $stream->write('4200aece1ce9000000097048597300000b1300000b1301009a9c180000000774494d4507d80c1a0a');
        $stream->write('1a1a8835e86f0000001974455874436f6d6d656e74004372656174656420776974682047494d5057');
        $stream->write('810e170000007d4944415438cbad93b10dc0200c049d17127364ff79523347ba74c809fe3782b8c4');
        $stream->write('3e3d70705cb65ec5ccce7b856cd5b011ece056add53ccdcf606c0b9226f793877c5ff417f44a667c');
        $stream->write('4806db1e794606f08717640c8fa3ec21fce4595861fea0ad687f487d0a1e333e198f94143cb424dd');
        $stream->write('2a33189bd9f25cf437d4f500102731b5b67102460000000049454e44ae426082');
    }


    /**
     * gets file as hex encoded
     *
     * @return string hex code
     */
    private function addImageAsHexToStream()
    {
        // if file does not exist, show missing image
        if ($this->_file === null) {
            $this->addMissingFileToStream();
        }

        $fh = @fopen($this->_file, 'rb');
        if (!$fh) {
            $this->addMissingFileToStream();
        }
        else {
            $stream = $this->_rtf->getStream();

            while (!feof($fh)) {
                $stringBuffer = fread($fh, 1024);
                $stringHex = '';
                for ($i = 0; $i < strlen($stringBuffer); $i++) {
                    $hex = dechex(ord($stringBuffer[$i]));
                    if (strlen($hex) == 1) {
                        $hex = '0' . $hex;
                    }
                    $stringHex .= $hex;
                }
                $stream->write($stringHex);
            }

            fclose($fh);
        }
    }


    /**
     * gets rtf code of image
     *
     * @return string rtf code
     */
    public function render()
    {
        $stream = $this->_rtf->getStream();

        $stream->write('{\pict');

        if ($this->_border) {
            $stream->write($this->_border->getContent());
        }

        $stream->write('\picwgoal' . $this->getImageWidth());
        $stream->write('\pichgoal' . $this->getImageHeight());

        if ($this->_file) {
            $pathInfo = pathinfo($this->_file);
            $extension = isset($pathInfo['extension'])
                         ? strtolower($pathInfo['extension'])
                         : '';
        }
        else {
            $extension = 'png';
        }

        if ($extension == 'png') {
            $stream->write('\pngblip ');
        }
        else {
            $stream->write('\jpegblip ');
        }

        $this->addImageAsHexToStream();

        $stream->write('}');
    }

}
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
 * Class for displaying images in rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2010 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_Image {

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
     * @var string
     */
    protected $_extension;

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
     * Constructor
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

        if (file_exists($imageFile)) {
            $this->_file = $imageFile;
            $pathInfo = pathInfo($imageFile);

            if (isset($pathInfo['extension'])) {
                $this->_extension = strtolower($pathInfo['extension']);
            }

            list($this->_defaultWidth, $this->_defaultHeight) = getimagesize($imageFile);

            if ($width !== null) {
                $this->setWidth($width);
            }

            if ($height !== null) {
                $this->setHeight($height);
            }
        }
        else {
            $this->_defaultWidth = 20;
            $this->_defaultHeight = 20;
            $this->_extension = 'png';
        }
    }

    /**
     * Sets paragraph format for image
     *
     * @param PHPRtfLite_ParFormat $parFormat
     */
    public function setParFormat(PHPRtfLite_ParFormat $parFormat) {
        $this->_parFormat = $parFormat;
    }

    /**
     * Gets paragraph format for image
     * 
     * @return PHPRtfLite_ParFormat
     */
    public function getParFormat() {
        return $this->_parFormat;
    }

    /**
     * Sets image width
     *
     * @param   float   $width  if not defined image is displayed by it's height.
     */
    public function setWidth($width) {
        $this->_width = $width;
    }

    /**
     * Gets image width
     * 
     * @return float
     */
    public function getWidth() {
        return $this->_width;
    }

    /**
     * Sets image height
     *
     * @param   float   $height if not defined image is displayed by it's width.
     */
    public function setHeight($height) {
        $this->_height = $height;
    }

    /**
     * Gets image height
     * 
     * @return float
     */
    public function getHeight() {
        return $this->_height;
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
     * Gets rtf image width
     * 
     * @return integer
     */
    private function getImageWidth() {
        if ($this->_width > 0) {
            $width = $this->_width;
        }
        elseif ($this->_height > 0) {
            $width = ($this->_defaultWidth / $this->_defaultHeight) * $this->_height;
        }
        else {
            $width = $this->_defaultWidth * PHPRtfLite::CM_IN_POINTS;
        }

        return round($width * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets rtf image height
     *
     * @return integer
     */
    private function getImageHeight() {
        if ($this->_height > 0) {
            $height = $this->_height;
        }
        elseif ($this->_width > 0) {
            $height= ($this->_defaultHeight /$this->_defaultWidth) * $this->_width;
        }
        else {
            $height = $this->_defaultHeight * PHPRtfLite::CM_IN_POINTS;
        }

        return round($height * PHPRtfLite::TWIPS_IN_CM);
    }

    /**
     * Gets file as hex encoded
     *
     * @return string hex code
     */
    private function getFileAsHex() {
        // if file does not exist, show missing image
        if ($this->_file === null) {
            return '89504e470d0a1a0a0000000d494844520000001400000014080200000002eb8a5a000000017352474200aece1ce9000000097048597300000b1300000b1301009a9c180000000774494d4507d80c1a0a1a1a8835e86f0000001974455874436f6d6d656e74004372656174656420776974682047494d5057810e170000007d4944415438cbad93b10dc0200c049d17127364ff79523347ba74c809fe3782b8c43e3d70705cb65ec5ccce7b856cd5b011ece056add53ccdcf606c0b9226f793877c5ff417f44a667c4806db1e794606f08717640c8fa3ec21fce4595861fea0ad687f487d0a1e333e198f94143cb424dd2a33189bd9f25cf437d4f500102731b5b67102460000000049454e44ae426082';
        }

        $imageData = file_get_contents($this->_file);
        $size = filesize($this->_file);

        $hexString = '';

        for ($i = 0; $i < $size; $i++) {
            $hex = dechex(ord($imageData{$i}));

            if (strlen($hex) == 1) {
                $hex = '0' . $hex;
            }

            $hexString .= $hex;
        }

        return $hexString;
    }

    /**
     * Gets rtf code of image
     *
     * @return string rtf code
     */
    public function getContent() {
        $content = '{\pict';

        if ($this->_border) {
            $content .= $this->_border->getContent($this->_rtf);
        }

        $content .= '\picwgoal' . $this->getImageWidth();
        $content .= '\pichgoal' . $this->getImageHeight();

        switch ($this->_extension) {
            case 'jpeg':
            case 'jpg':
                $content .= '\jpegblip ';
                break;

            case 'png':
                $content .= '\pngblip ';
                break;
        }

        $content .= $this->getFileAsHex();
        $content .= '}';
        
        return $content;
    }

    
    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setBorder() instead
     * @see PHPRtfLite/PHPRtfLite_Image#setBorder()
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
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
 * Class for creating rtf documents.
 * @version     1.0.0
 * @author      Denis Slaveckij <info@phprtf.com>, Steffen Zeidler <sigma_z@web.de>
 * @copyright   2007-2008 Denis Slaveckij, 2009 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite {

    const SPACE_IN_POINTS           = 20;
    const SPACE_IN_LINES            = 240;
    const TWIPS_IN_CM               = 567;
    const CM_IN_POINTS              = 0.026434;

    /**
     * constants defining view modes
     */
    const VIEW_MODE_NONE            = 0;
    const VIEW_MODE_PAGE_LAYOUT     = 1;
    const VIEW_MODE_OUTLINE         = 2;
    const VIEW_MODE_MASTER          = 3;
    const VIEW_MODE_NORMAL          = 4;
    const VIEW_MODE_ONLINE_LAYOUT   = 5;

    /**
     * constants defining zoom modes
     */
    const ZOOM_MODE_NONE            = 0;
    const ZOOM_MODE_FULL_PAGE       = 1;
    const ZOOM_MODE_BEST_FIT        = 2;


    /**
     * rtf sections
     * @var array
     */
    protected $_sections    = array();

    /**
     * rtf headers
     * @var array
     */
    protected $_headers     = array();

    /**
     * rtf footers
     * @var array
     */
    protected $_footers     = array();

    /**
     * paper width
     * @var integer
     */
    protected $_paperWidth   = 21;

    /**
     * paper height
     * @var integer
     */
    protected $_paperHeight  = 29;

    /**
     * left margin
     * @var integer
     */
    protected $_marginLeft  = 3;

    /**
     * right margin
     * @var integer
     */
    protected $_marginRight = 3;

    /**
     * top margin
     * @var integer
     */
    protected $_marginTop   = 1;

    /**
     * bottom margin
     * @var integer
     */
    protected $_marginBottom = 2;

    /**
     * flag, if true, even and odd pages are using different layouts
     * @var boolean
     */
    protected $_useOddEvenDifferent = false;

    /**
     * rtf code
     * @var string
     */
    protected $_content;

    /**
     * rtf fonts
     * @var array
     */
    protected $_fonts           = array();

    /**
     * rtf colors
     * @var array
     */
    protected $_colors          = array('\red0\green0\blue0' => 1);

    /**
     * rtf properties
     * @var array
     */
    protected $_properties      = array();

    /**
     * default tab width
     * @var float
     */
    protected $_defaultTabWidth = 2.29;

    /**
     * view mode
     * @var string
     */
    protected $_viewMode;

    /**
     * zoom level
     * @var integer
     */
    protected $_zoomLevel;

    /**
     * zoom mode
     * @var integer
     */
    protected $_zoomMode;

    /**
     * gutter
     * @var float
     */
    protected $_gutter;

    /**
     * flag, if true margins will be the opposite for odd and even pages
     * @var boolean
     */
    protected $_useMirrorMargins = false;

    /**
     * start with page number
     * @var integer
     */
    protected $_pageNumberStart = 1;

    /**
     * flag, if true first page has special layout
     * @var boolean
     */
    protected $_titlepg         = false;

    /**
     * rtf border
     * @var PHPRtfLite_Border
     */
    protected $_border;

    /**
     * flag, if true use landscape layout
     * @var boolean
     */
    protected $_isLandscape         = false;

    /**
     * document head definition for notes
     * @var PHPRtfLite_DocHeadDefinition_Note
     */
    private $_noteDocHeadDefinition = null;


    /**
     * Constructor
     */
    public function __construct() {
        $this->addFont('Times new Roman');
    }

    /**
     * Registers autoloader for PHPRtfLite classes
     */
    static public function registerAutoloader() {
        $baseClassDir = dirname(__FILE__);
        require $baseClassDir . '/PHPRtfLite/Autoloader.php';
        PHPRtfLite_Autoloader::setBaseDir($baseClassDir);
        spl_autoload_register(array('PHPRtfLite_Autoloader', 'autoload'));
    }

    /**
     * Sets document information properties.
     * @param string $property Property of document. Possible properties: <br>
     *   'title' => title of the document (value string)<br>
     *   'subject' => subject of the document (value string)<br>
     *   'author' => author of the document (value string)<br>
     *   'manager' => manager of the document (value string)<br>
     *   'company' => company of author (value string)<br>
     *   'operator' => operator of document. Operator is a person who last made changes to the document. (value string) <br>
     *   'category' => category of document (value string)<br>
     *   'keywords' => keywords of document (value string)<br>
     *   'doccomm' => comments of document (value string)<br>
     *   'creatim' => creation time (value int) <br>
     *   'revtim' => last revision time (value int) <br>
     *   'buptim' => last backup time (value int) <br>
     *   'printim' => last print time (value int) <br>
     * @param mixed $value Value
     */
    public function setProperty($name, $value) {
        switch ($name) {
            case 'creatim':
            case 'revtim':
            case 'buptim':
            case 'printim':
                $year       = date('Y', $value);
                $month      = date('m', $value);
                $day        = date('d', $value);
                $month      = date('m', $value);
                $hours      = date('H', $value);
                $minutes    = date('i', $value);

                $value = '\yr' . $year
                       . '\mo' . $month
                       . '\dy' . $day
                       . '\hr' . $hours
                       . '\min' . $minutes;
                break;
            default:
                $value = str_replace('\\', '\\\\', $value);
        }

        $this->_properties[$name] = $value;
    }

    /**
     * gets document head definition for notes
     *
     * @return PHPRtfLite_DocHeadDefinition_Note
     */
    public function getNoteDocHeadDefinition() {
        if ($this->_noteDocHeadDefinition === null) {
            $this->_noteDocHeadDefinition = new PHPRtfLite_DocHeadDefinition_Note();
        }

        return $this->_noteDocHeadDefinition;
    }

    /**
     * Gets rtf property
     *
     * @param   string $name
     * @return  string
     */
    public function getProperty($name) {
        return isset($this->_property[$name])
               ? $this->_property[$name]
               : null;
    }

    /**
     * sets footnote numbering type
     *
     * @param integer $numberingType
     */
    public function setFootnoteNumberingType($numberingType) {
        $this->getNoteDocHeadDefinition()->setFootnoteNumberingType($numberingType);
    }

    /**
     * gets footnote numbering type
     *
     * @return integer
     */
    public function getFootnoteNumberingType() {
        return $this->getNoteDocHeadDefinition()->getFootnoteNumberingType();
    }

    /**
     * sets endnote numbering type
     *
     * @param integer $numberingType
     */
    public function setEndnoteNumberingType($numberingType) {
        $this->getNoteDocHeadDefinition()->setEndnoteNumberingType($numberingType);
    }

    /**
     * gets endnote numbering type
     *
     * @return integer
     */
    public function getEndnoteNumberingType() {
        return $this->getNoteDocHeadDefinition()->getEndnoteNumberingType();
    }

    /**
     * sets footnote start number
     *
     * @param integer $startNumber
     */
    public function setFootnoteStartNumber($startNumber) {
        $this->getNoteDocHeadDefinition()->setFootnoteStartNumber($startNumber);
    }

    /**
     * gets footnote start number
     *
     * @return integer
     */
    public function getFootnoteStartNumber() {
        return $this->getNoteDocHeadDefinition()->getFootnoteStartNumber();
    }

    /**
     * sets endnote start number
     *
     * @param integer $startNumber
     */
    public function setEndnoteStartNumber($startNumber) {
        $this->getNoteDocHeadDefinition()->setEndnoteStartNumber($startNumber);
    }

    /**
     * gets endnote start number
     *
     * @return integer
     */
    public function getEndnoteStartNumber() {
        return $this->getNoteDocHeadDefinition()->getEndnoteStartNumber();
    }

    /**
     * sets restart footnote number on each page
     */
    public function setRestartFootnoteNumberEachPage() {
        $this->getNoteDocHeadDefinition()->setRestartFootnoteNumberEachPage();
    }

    /**
     * checks, if footnote numbering shall be started on each page
     *
     * @return boolean
     */
    public function setRestartEndnoteNumberEachPage() {
        $this->getNoteDocHeadDefinition()->setRestartEndnoteNumberEachPage();
    }

    /**
     * sets restart endnote number on each page
     */
    public function isRestartFootnoteNumberEachPage() {
        return $this->getNoteDocHeadDefinition()->isRestartFootnoteNumberEachPage();
    }

    /**
     * checks, if endnote numbering shall be started on each page
     *
     * @return boolean
     */
    public function isRestartEndnoteNumberEachPage() {
        return $this->getNoteDocHeadDefinition()->isRestartEndnoteNumberEachPage();
    }

    /**
     * sets default font for notes
     *
     * @param PHPRtfLite_Font $font
     */
    public function setDefaultFontForNotes(PHPRtfLite_Font $font) {
        PHPRtfLite_Note::setDefaultFont($font);
    }

    /**
     * gets default font for notes
     *
     * @return PHPRtfLite_Font
     */
    public function getDefaultFontForNotes() {
        return PHPRtfLite_Note::getDefaultFont($font);
    }

    /**
     * Adds section to rtf document.
     *
     * @return PHPRtfLite_Container_Section
     */
    public function addSection() {
        $section = new PHPRtfLite_Container_Section($this);

        if (count($this->_sections) == 0) {
            $section->setFirst(true);
        }

        $this->_sections[] = $section;

        return $section;
    }

    /**
     * Sets default tab width of the document.
     * @param float $defaultTabWidth Default tab width
     */
    public function setDefaultTabWidth($defaultTabWidth) {
        $this->_defaultTabWidth = $defaultTabWidth;
    }

    /**
     * Gets default tab width of the document.
     * @return float $defaultTabWidth Default tab width
     */
    public function getDefaultTabWidth() {
        return $this->_defaultTabWidth;
    }

    /**
     * Sets the paper width of document.
     * @param float $width pager width
     */
    public function setPaperWidth($width) {
        $this->_paperWidth = $width;
    }

    /**
     * gets the paper width of document.
     * @return float $paperWidth paper width
     */
    public function getPaperWidth() {
        return $this->_paperWidth;
    }

    /**
     * Sets the paper height of document.
     * @param float $height paper height
     */
    public function setPaperHeight($height) {
        $this->_paperHeight = $height;
    }

    /**
     * gets the paper height of document.
     * @return float $paperHeight paper height
     */
    public function getPaperHeight() {
        return $this->_paperHeight;
    }

    /**
     * Sets the margins of document pages.
     * @param float $marginLeft     Margin left (default 3 cm)
     * @param float $marginTop      Margin top (default 1 cm)
     * @param float $marginRight    Margin right (default 3 cm)
     * @param float $marginBottom   Margin bottom (default 2 cm)
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
     * Sets the margin definitions on left and right pages. <br>
     * NOTICE: Does not work with OpenOffice.
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
     * Sets the gutter width. <br>
     * NOTICE: Does not work with OpenOffice.
     * @param float $gutter gutter width
     */
    public function setGutter($gutter) {
        $this->_gutter = $gutter;
    }

    /**
     * Gets the gutter width
     * @return float $gutter gutter width
     */
    public function getGutter() {
        return $this->_gutter;
    }

    /**
     * Sets the beginning page number.
     * @param integer $startPage Beginning page number (if not defined, Word uses 1)
     */
    public function setPageNumberStart($pageNumber) {
        $this->_pageNumberStart = $pageNumber;
    }

    /**
     * Gets the beginning page number.
     * @return integer
     */
    public function getPageNumberStart() {
        return $this->_pageNumberStart;
    }

    /**
     * Sets the view mode of the document.
     * @param integer $viewMode View Mode. Represented as class constants VIEW_MODE_*<br>
     *   Possible values: <br>
     *     VIEW_MODE_NONE           => 0 - None <br>
     *     VIEW_MODE_PAGE_LAYOUT    => 1 - Page Layout view <br>
     *     VIEW_MODE_OUTLINE        => 2 - Outline view <br>
     *     VIEW_MODE_MASTER         => 3 - Master Document view <br>
     *     VIEW_MODE_NORMAL         => 4 - Normal view <br>
     *     VIEW_MODE_ONLINE_LAYOUT  => 5 - Online Layout view
     */
    public function setViewMode($viewMode) {
        $this->_viewMode = $viewMode;
    }

    /**
     * Gets the view mode of the document.
     * @return integer view mode represented as class constants VIEW_MODE_*
     */
    public function getViewMode() {
        return $this->_viewMode;
    }

    /**
     * Sets the zoom level (in percents) of the document. By default word uses 100%. <br>
     * NOTICE: if zoom mode is defined, zoom level is not used.
     * @param integer $zoom zoom level
     */
    public function setZoomLevel($zoom) {
        $this->_zoomLevel = $zoom;
    }

    /**
     * Gets the zoom level (in percents) of the document.
     * @return integer $zoom zoom level
     */
    public function getZoomLevel() {
        return $this->_zoomLevel;
    }

    /**
     * Sets the zoom mode of the document.
     * @param integer $zoomMode zoom mode. Represented as class constants.
     *   Possible values: <br>
     *     ZOOM_MODE_NONE       => 0 - None <br>
     *     ZOOM_MODE_FULL_PAGE  => 1 - Full Page <br>
     *     ZOOM_MODE_BEST_FIT   => 2 - Best Fit
     */
    public function setZoomMode($zoomMode) {
        $this->_zoomMode = $zoomMode;
    }

    /**
     * Gets the zoom mode of the document.
     * @return integer
     */
    public function getZoomMode() {
        return $this->_zoomMode;
    }

    /**
     * Sets landscape orientation of paper.
     */
    public function setLandscape() {
        $this->_isLandscape = true;
    }

    /**
     * Returns true, if landscape layout should be used
     * @return boolean
     */
    public function isLandscape() {
        return $this->_isLandscape;
    }

    /**
     * Formats color code.
     * @static
     * @param string $color Color
     *
     * @return string rtf color
     * @throws PHPRtfLite_Exception, if color is not a 3or 6 digit hex number
     */
    static public function convertHexColorToRtf($color) {
        $color = ltrim($color, '#');

        if (strlen($color) == 3) {
            $red    = hexdec(str_repeat(substr($color, 0, 1), 2));
            $green  = hexdec(str_repeat(substr($color, 1, 1), 2));
            $blue   = hexdec(str_repeat(substr($color, 2, 1), 2));

            return '\red' . $red . '\green' . $green . '\blue' . $blue;
        }
        elseif (strlen($color) == 6) {
            $red    = hexdec(substr($color, 0, 2));
            $green  = hexdec(substr($color, 2, 2));
            $blue   = hexdec(substr($color, 4, 2));

            return '\red' . $red . '\green' . $green . '\blue' . $blue;
        }

        throw new PHPRtfLite_Exception('Color must be a hex number of length 3 or 6 digits! You gave me: #' . $color);
    }


    /**
     * Sets border to rtf document. Sections may override this border.
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
     * Sets borders to rtf document. Sections may override this border.
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
     * Sets if odd and even headers/footers are different
     */
    public function setOddEvenDifferent($different = true) {
         $this->_useOddEvenDifferent = $different;
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
     * Creates header for the document.
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
        $header = new PHPRtfLite_Container_Header($this, $type);
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
        $footer = new PHPRtfLite_Container_Footer($this, $type);
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
     * Saves rtf document to file.
     * @param string Name of file
     */
    public function save($file) {
        $this->prepare();
        file_put_contents($file, $this->_content);
    }

    /**
     * Sends rtf content as file attachment.
     * @param string $file Name of file
     */
    public function sendRtf($file = 'simple') {
        $this->prepare();

        $pathInfo = pathinfo($file);

        if (empty($pathInfo['extension'])) {
            $file .= '.rtf';
        }

        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.5')) {
            header('Content-Disposition: filename="' . $file . '"');
        }
        else {
            header('Content-Disposition: attachment; filename="' . $file . '"');
        }
        header('Content-type: application/msword');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");

        echo $this->_content;
    }


    /**
     * Adds font to rtf document.
     * @param string $font font name
     */
    public function addFont($font) {
        if (!empty($font)) {
            if (!isset($this->_fonts[$font])) {
                $count = count($this->_fonts);
                $this->_fonts[$font] = '\f' . $count;
            }
        }
    }

    /**
     * Adds color to rtf document.
     * @param string $color color
     */
    public function addColor($color) {
        if (!empty($color)) {
            if (!isset($this->_colors[$color])) {
                $count = count($this->_colors);
                $this->_colors[$color] = ($count + 1);
            }
        }
    }

    /**
     * Gets rtf code of font.
     * @param string $font font name
     * @return string
     */
    public function getFont($font) {
          return isset($this->_fonts[$font])
                 ? $this->_fonts[$font]
                 : '';
    }

    /**
     * Gets rtf code of color.
     * @param string $color color
     * @return string
     */
    public function getColor($color) {
        return isset($this->_colors[$color])
               ? $this->_colors[$color]
               : '';
    }

    /**
     * Gets rtf code of font color.
     * @param string color
     * @return string
     */
    public function getFontColor($fontColor) {
        if (isset($this->_colors[$fontColor])) {
            return '\cf' . $this->_colors[$fontColor];
        }

        return '';
    }

    /**
     * Gets rtf code of background color.
     * @param  string color hex code
     * @return string
     */
    public function getBackgroundColor($backgroundColor) {
        if (isset($this->_colors[$backgroundColor])) {
            return '\chcbpat' . $this->_colors[$backgroundColor];
        }

        return '';
    }

    /**
     * Gets rtf font table
     * @return string
     */
    protected function getFontTable() {
        $part = '{\fonttbl';

        foreach ($this->_fonts as $key => $value) {
            $part .= '{' . $value . ' ' . $key . ';}';
        }
        
        $part .= '}' . "\r\n";

        return $part;
    }

    /**
     * Gets rtf color table
     * @return string
     */
    protected function getColorTable() {
        $part = '{\colortbl;';
        
        foreach ($this->_colors as $key => $value) {
            $part .= $key . ';';
        }
        
        $part .= '}' . "\r\n";

        return $part;
    }

    /**
     * Gets rtf info part
     * @return string
     */
    protected function getInfoPart() {
        $part = '{\info'."\r\n";

        //@TODO rtf escapes?
        foreach ($this->_properties as $key => $value) {
            $part .= '{\\' . $key . ' ' . $value . '}'."\r\n";
        }

        $part .= '}'."\r\n";

        return $part;
    }

    /**
     * Sets that first page has a special layout
     * @param boolean $specialLayout
     */
    public function setFirstPageHasSpecialLayout($specialLayout = true) {
        $this->_titlepg = $specialLayout;
    }

    /**
     * Returns true, if first page has special layout
     * @return boolean
     */
    public function getFirstPageHasSpecialLayout() {
        return $this->_titlepg;
    }

    /**
     * quotes rtf code
     *
     * @param  string $text
     *
     * @return string
     */
    public static function quoteRtfCode($text) {
        // escape backslashes
        $text = addslashes($text);
        // convert breaks into rtf break
        $text = str_replace(array("\r\n", "\n", "\r"), '\par ', $text);

        return $text;
    }

    /**
     * prepares rtf contents
     */
    protected function prepare() {
        $part = $this->getInfoPart();

        //page properties
        $part .= $this->_isLandscape ? '\landscape ' : '';
        $part .= '\deftab' . round(self::TWIPS_IN_CM * $this->_defaultTabWidth) . ' ';
        $part .= '\paperw' . round(self::TWIPS_IN_CM * $this->_paperWidth)  .' ';
        $part .= '\paperh' . round(self::TWIPS_IN_CM * $this->_paperHeight) . ' ';
        $part .= '\margl' . round(self::TWIPS_IN_CM * $this->_marginLeft) . ' ';
        $part .= '\margr' . round(self::TWIPS_IN_CM * $this->_marginRight) . ' ';
        $part .= '\margt' . round(self::TWIPS_IN_CM * $this->_marginTop) . ' ';
        $part .= '\margb' . round(self::TWIPS_IN_CM * $this->_marginBottom) . ' ';

        if (null !== $this->_gutter) {
            $part .= '\gutter' . round($this->_gutter * self::TWIPS_IN_CM) . ' ';
        }

        if (true == $this->_useMirrorMargins) {
            $part .= '\margmirror ';
        }

        if (null !== $this->_viewMode) {
            $part .= '\viewkind' . $this->_viewMode . ' ';
        }

        if (null !== $this->_zoomMode) {
            $part .= '\viewzk' . $this->_zoomMode . ' ';
        }

        if (null !== $this->_zoomLevel) {
            $part .= '\viewscale' . $this->_zoomLevel . ' ';
        }

        if ($this->_sections[0] && $this->_sections[0]->getBorder()) {
            $part .= $this->_sections[0]->getBorder()->getContent($this, '\pg');
        }
        elseif ($this->_border) {
            $part .= $this->_border->getContent($this, '\pg');
        }

        //headers and footers properties
        if ($this->_useOddEvenDifferent) {
            $part .= '\facingp ';
        }
        if ($this->_titlepg) {
            $part .= '\titlepg ';
        }

        // document header definiton for footnotes and endnotes
        $part .= $this->getNoteDocHeadDefinition()->getContent();

        //headers and footers if there are no sections
        if (count($this->_sections) == 0) {
            foreach ($this->_headers as $header) {
                $part .= $header->getContent();
            }

            foreach ($this->_footers as $footer) {
                $part .= $footer->getContent();
            }
        }

        //sections
        foreach ($this->_sections as $key => $section) {
            $part .= $section->getContent();
        }

        $this->_content = '{\rtf\ansi\deff0 \r\n';
        $this->_content .= $this->getFontTable();
        $this->_content .= $this->getColorTable();
        $this->_content .= $part.'}';
    }


    //// DEPRECATED FUNCTIONS FOLLOWS HERE ////

    /**
     * @deprecated use setProperty() instead
     * @see PHPRtfLite/PHPRtfLite#setProperty()
     *
     * Sets property of info part of rtf document
     *
     * @param string    $name
     * @param mixed     $value
     */
    public function setInfo($name, $value) {
        $this->setProperty($name, $value);
    }

    /**
     * @deprecated use setPageNumberStart() instead
     * @see PHPRtfLite/PHPRtfLite#setPageNumberStart()
     *
     * Sets the beginning page for the rtf document
     *
     * @param integer $pageNumber
     */
    public function setStartPage($pageNumber) {
        $this->setPageNumberStart($pageNumber);
    }

    /**
     * @deprecated use setZoomLevel() instead
     * @see PHPRtfLite/PHPRtfLite#setZoomLevel()
     *
     * Sets the zoom level for the rtf document, will be ignored when using zoom mode
     *
     * @param integer $zoom
     */
    public function setZoom($zoom) {
        $this->setZoomLevel($zoom);
    }

    /**
     * @deprecated use setZoomMode() instead
     * @see PHPRtfLite/PHPRtfLite#setZoomMode()
     *
     * Sets the zoom mode for the rtf document
     *
     * @param integer $zoomKind 
     */
    public function setZoomKind($zoomKind) {
        $this->setZoomMode($zoomKind);
    }

    /**
     * @deprecated use setBackgroundColor() instead
     * @see PHPRtfLite/PHPRtfLite#setBackgroundColor()
     *
     * Sets background color of the rtf document
     *
     * @param  string color (hex code)
     * 
     * @return string rtf background color
     */
    public function getBackColor($color) {
        return $this->getBackgroundColor($color);
    }

}
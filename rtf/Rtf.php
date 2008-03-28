<?php
/* 
 	PhpRtf Lite
	Copyright 2007-2008 Denis Slaveckij <info@phprtf.com>  	

	This file is part of PhpRtf Lite.

    PhpRtf Lite is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PhpRtf Lite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with PhpRtf Lite.  If not, see <http://www.gnu.org/licenses/>.
*/

define('SPACE_IN_POINTS', 20);
define('SPACE_IN_LINES', 240);
define('TWIPS_IN_CM', 567);

define('RTF_DEFAULT_TAB_WIDTH', 720);

require_once('Util.php');
require_once('Container.php');
require_once('Header.php');
require_once('Footer.php');
require_once('Section.php');
require_once('Font.php');
require_once('Bordered.php');
require_once('ParFormat.php');
require_once('Table.php');
require_once('Cell.php');
require_once('Image.php');
require_once('BorderFormat.php');

/**
 * Class for creating rtf documents.
 * @version 0.3.0
 * @author Denis Slaveckij <info@phprtf.com>
 * @copyright  2007-2008 Denis Slaveckij
 * @package Rtf
 */
class Rtf {
  
	/**#@+ 
	 * Internal use.
	 * @access public
	 */ 
	var $sections = array();
	
	var $headers = array();
	
	var $footers = array();
	
	var $paperWidth = 21;  	
	
	var $paperHeight = 29;
	  	
	var $marginLeft = 3;
	
	var $marginRight = 3;
	
	var $marginTop = 1;
	
	var $marginBottom = 2;
	
	var $oddEvenDifferent;	
	/**#@-*/  	  	
	
	/**#@+ @access private */    		  
	var $content;
	
	var $fonts = array();  	
	
	var $colors = array('\red0\green0\blue0' => 1);
	
	var $info = array();
	  	    	
	var $defaultTabWidth = 2.29;
		    	
	var $viewMode;
	
	var $zoom;
	
	var $zoomKind; 	
	  	  	
	var $gutter;  	
	  	
	var $mirrorMargins;
	  	
	var $startPage;				
	
	var $titlepg = 0;
			
	var $bordered;
	
	var $landscape;
	/**#@-*/  	  	
	
	/**
	 * Rtf constructor.
	 * @access public
	 */
	function Rtf() {	  
		$this->addFont('Times new Roman');	
	}  
	   
	/**
	 * SetS document information properties. 
	 * @param string $property Property of document. Possible properties: <br>
	 * 'title' => title of the document (value string)<br>
	 * 'subject' => subject of the document (value string)<br>
	 * 'author' => author of the document (value string)<br>
	 * 'manager' => manager of the document (value string)<br>
	 * 'company' => company of author (value string)<br>
	 * 'operator' => operator of document. Operator is a person who last made changes to the document. (value string) <br>
	 * 'category' => category of document (value string)<br>
	 * 'keywords' => keywords of document (value string)<br>
	 * 'doccomm' => comments of document (value string)<br>	 
	 * 'creatim' => creation time (value int) <br>
	 * 'revtim' => last revision time (value int) <br>
	 * 'buptim' => last backup time (value int) <br>
	 * 'printim' => last print time (value int) <br>
	 * @param mixed $value Value
	 */    
	function setInfo($property, $value) {	  
		if (is_string($value) && in_array($property, array('title', 'subject', 'author', 'manager', 'company', 'operator', 'category', 'keywords', 'comments', 'doccomm', 'hlinkbase'))) {		  
		  	$this->info[$property] = str_replace('\\', '\\\\', $value);
		} else if (is_int($value) && in_array($property, array('creatim', 'revtim', 'buptim', 'printim'))) {		  		  	
			$year = date("Y", $value);
		  	$month = date("m", $value);
		  	$day = date("d", $value);
		  	$month = date("m", $value);
		  	$hours = date("H", $value);
		  	$minutes = date("i", $value);
		  	
		  	$this->info[$property] = '\yr'.$year.'\mo'.$month.'\dy'.$day.'\hr'.$hours.'\min'.$minutes;	  
		} else {

		}
	}			
		
	/**
	 * Adds section to rtf document.
	 * @access public
	 * @return Section
	 */  	
	function &addSection() {	    
	    $section = new Section($this);
	
		if (count($this->sections) == 0) {		  
		  	$section->first = true;
		}
	
	    $this->sections[] = &$section;		
			    
	    return $section;
	}
	
	/**
	 * Sets default tab width of the document.
	 * @access public 
	 * @param float $defaultTabWidth Default tab width
	 */
	function setDefaultTabWidth($defaultTabWidth) {	 
	 	$this->defaultTabWidth = $defaultTabWidth;
	}
	  		  
	/**
	 * Sets the paper width of document.    
	 * @access public
	 * @param float $paperWidth Paper width
	*/  	
	function setPaperWidth($paperWidth) {	    
	    $this->paperWidth = $paperWidth;
	}
	
	/**
	 * Sets the paper height of document.    
	 * @access public
	 * @param float $paperHeight Paper height
	 */  	
	function setPaperHeight($paperHeight) {	    
	    $this->paperHeight = $paperHeight;
	}
	
	/**
	 * Sets the margins of document pages.    
	 * @access public
	 * @param float $marginLeft Margin left (default 3 cm)
	 * @param float $marginTop Margin top (default 1 cm)
	 * @param float $marginRight Margin right (default 3 cm)
	 * @param float $marginBottom Margin bottom (default 2 cm)
	 */
	function setMargins($marginLeft, $marginTop, $marginRight, $marginBottom) {	  
		$this->marginLeft = $marginLeft;  
		$this->marginTop = $marginTop;
		$this->marginRight = $marginRight;
		$this->marginBottom = $marginBottom;
	}
	
	/**
	 * Sets the margin definitions on left and right pages. <br>    
	 * NOTICE: OpenOficce doesn't Understant.
	 * @access public
	 */  	
	function setMirrorMargins() {	    
	    $this->mirrorMargins = true;
	}
	
	/**
	 * Sets the gutter width. <br>   
	 * NOTICE: OpenOficce doesn't understant.
	 * @access public
	 * @param float $gutter Gutter width
	 */  	
	function setGutter($gutter) {	    
	    $this->gutter = $gutter;
	}	
	
	/**
	 * Sets the beginning page number.   
	 * @access public
	 * @param float $startPage Beginning page number (if not defined, Word understands as 1)
	 */  	
	function setStartPage($startPage) {	    	    
		$this->startPage = $startPage;
	}
	
	/**
	 * Sets the view mode of the document.
	 * @access public
	 * @param integer (0 - 5) $viewMode View Mode. Possible values: <br>
	 * '0' =>  None <br> 
	 * '1' => Page Layout view <br>
	 * '2' - Outline view <br>
	 * '3' - Master Document view <br>
	 * '4' - Normal view <br>
	 * '5' - Online Layout view
	 */  
	function setViewMode($viewMode) {	  
		$this->viewMode = $viewMode;
	}
	
	/**
	 * Sets the zoom level (in percents) of the document. By default word understands as 100%. <br>
	 * NOTICE: if zoom kind is defined, zoom level is not used.
	 * @access public
	 * @param integer $zoom Zoom Level 	
	 */  
	function setZoom($zoom) {	  
		$this->zoom = $zoom;
	}
	
	/**
	 * Sets the zoom kind of the document.
	 * @access public
	 * @param integer $zoomKind Zoom kind. Possible values: <br>
	 * '0' => None <br>
	 * '1' => Full Page <br>
	 * '2' => Best Fit
	 */
	function setZoomKind($zoomKind) {	  
		$this->zoomKind = $zoomKind;
	}
	
	/**
	 * Sets landscape orientation of paper.
	 */
	function setLandscape() {
		$this->landscape = true;
	}
	
	/**
	 * Sets borders of document pages. Sections can override this borders.    
	 * @param BorderFormat &$borderFormat
	 * @param boolean $left If false, left border is not set (default true)
	 * @param boolean $top If false, top border is not set (default true)
	 * @param boolean $right If false, right border is not set (default true)
	 * @param boolean $bottom If false, bottom border is not set (default true)
	 * @access public    
	 */	
	function setBorders(&$borderFormat, $left = true, $top = true, $right = true, $bottom = true) {	  
		if (empty($this->bordered)) {		  
		  	$this->bordered = new Bordered();
		}
		
		$this->bordered->setBorders($borderFormat, $left, $top, $right, $bottom);
	}
		
	/**
	 * Sets if odd and even headers/footers are different
	 * @access public
	 */
	function setOddEvenDifferent() {	 
	 	$this->oddEvenDifferent = 1;	 
	}
	
	/**
	 * Creates header for document pages.
	 * @param string $type Possible values: <br>
	 * 'all' => all pages (different odd and even headers/footers must be not set) <br>
	 * 'left' => left pages (different odd and even headers/footers must be set) <br>
	 * 'right' => right pages (different odd and even headers/footers must be set) <br>	
	 * 'first' => first page  	 
	 * @access public
	 * @return Header
	 */
	function &addHeader($type = 'all') {
	  	if (empty($this->oddEvenDifferent) && $type == 'all') {
		    $header = new Header($this, $type);
		} else if (!empty($this->oddEvenDifferent) 
			&& ($type == 'left' || $type == 'right')) 
		{		  
		  	$header = new Header($this, $type);	
		} else if ($type == 'first') {
		  	$header = new Header($this, $type);	
		  	$this->titlepg = 1;
		} else {	
		  	echo 'Error: headers';
		  	return;
		}		 
	
		$this->headers[$type] = &$header;
		return $header;		
	} 	
	
	/**
	 * Creates footer for document pages.
	 * @param string $type Possible values: <br>
	 * 'all' => all pages (different odd and even headers/footers must be not set) <br>
	 * 'left' => left pages (different odd and even headers/footers must be set) <br>
	 * 'right' => right pages (different odd and even headers/footers must be set) 	<br>
	 * 'first' => first page  	 
	 * @access public
	 * @return Footer
	 */
	function &addFooter($type = 'all') {
	  	if (empty($this->oddEvenDifferent) && $type == 'all') {
		    $footer = new Footer($this, $type);
		} else if (!empty($this->oddEvenDifferent) 
					&& ($type == 'left' || $type == 'right')) {		  
		  	$footer = new Footer($this, $type);	
		} else if ($type == 'first') {
		  	$footer = new Footer($this, $type);	
		  	$this->titlepg = 1;
		} else {				
		  	return;
		}		 
	
		$this->footers[$type] = &$footer;
		return $footer;		
	} 
	
	/**
	 * Saves rtf document to file.
	 * @param string Name of file
	 * @access public
	 */
	function save($fileName) {	
		$this->prepare();  
	 	$file = fopen($fileName, 'w');
	    fwrite($file, $this->content);
	    fclose($file);	  	
	}  	
	
	/**
	 * Sends rtf content as file attachment.
	 * @param string $fileName Name of file
	 * @access public
	 */
	function sendRtf($fileName = "simple") {	
		$this->prepare();			
		header('Content-Disposition: attachment; filename='.$fileName.'.rtf');
		header('Content-type: application/msword'); 
		header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	    header("Pragma: public");
		echo $this->content;
	}
	
	/////////////////////////////
		
	/**
	 * Adds font to rtf document. Internal use. 
	 * @param string $font Font
	 * @access public
	 */
	function addFont($font) {  
	  	if (!empty($font)) {		  		  	
		  	if (empty($this->fonts[$font])) {			    
				$count = count($this->fonts);
				$this->fonts[$font] = '\f'.$count;
			}
		}		
	}
	
	/** 
	 * Adds color to rtf document. Internal use.
	 * @param string $color Color
	 * @access public 
	 */	
	function addColor($color) {
		if (!empty($color)) {		  	
			if (empty($this->colors[$color])) {	
				$count = count($this->colors);
				$this->colors[$color] = ($count + 1);				
			}					  	
		}
	}
	
	/** 
	 * Gets rtf code of font. Internal use.
	 * @param string $font Font
	 * @return string
	 * @access public
	 */
	function getFont($font) {	  
	  	return $this->fonts[$font];
	}
	
	/** 
	 * Gets rtf code of color. Internal use.
	 * @param string $color Color
	 * @return string
	 * @access public
	 */
	function getColor($color) {	  
		return $this->colors[$color];  
	}
	
	/** 
	 * Gets rtf code of font color. Internal use.
	 * @param string Font color
	 * @return string
	 * @access public
	 */
	function getFontColor($fontColor) {	  
	  	return '\cf'.$this->colors[$fontColor];
	}
	
	/** 
	 * Gets rtf code of background color. Internal use.
	 * @param string Background color
	 * @return string
	 * @access public
	 */
	function getBackColor($backColor) {
		return '\chcbpat'.$this->colors[$backColor];
	}	
	
	/** @access private */ 
	function getFontTable() {  	 
		$part = '{\fonttbl';
	    foreach ($this->fonts as $key => $value) {		  
		  	$part .= '{'.$value.' '.$key.';}';	
		}
		$part .= '}'."\r\n";		    
		return $part;
	}
	
	/** @access private */
	function getColorTable() {
		$part = '{\colortbl;';
	    foreach ($this->colors as $key => $value) {		  
		  	$part .= $key.';';	
		}
		$part .= '}'."\r\n";		    
		return $part;
	}	
	
	/** @access private */
	function getInfoPart() {	  
	  	$part = '{\info'."\r\n";	    
	    foreach ($this->info as $key => $value) {		  
		  	$part .= '{\\'.$key.' '.$value.'}'."\r\n";
		}	    								
	    $part .= '}'."\r\n";			    
	    return $part;
	}	
		 
	/** @access private */
	function prepare() {	  					    
		$part = $this->getInfoPart();
		
		//page properties
		$part .= !empty($this->landscape) ? '\landscape ' : '';
		
		$part .= '\deftab'.round(TWIPS_IN_CM * $this->defaultTabWidth).' '; 
			    
		$part .= '\paperw'.round(TWIPS_IN_CM * $this->paperWidth).' '; 
		
		$part .= '\paperh'.round(TWIPS_IN_CM * $this->paperHeight).' ';
		
		$part .= '\margl'.round(TWIPS_IN_CM * $this->marginLeft).' ';  
			    
		$part .= '\margr'.round(TWIPS_IN_CM * $this->marginRight).' '; 
			    
		$part .= '\margt'.round(TWIPS_IN_CM * $this->marginTop).' ';  
			    
		$part .= '\margb'.round(TWIPS_IN_CM * $this->marginBottom).' ';
		
		if (isSet($this->gutter)) {		  	
			$part .= '\gutter'.round($this->gutter * TWIPS_IN_CM).' '; 
		}
		
		if (!empty($this->mirrorMargins)) {		  	
			$part .= '\margmirror '; 
		}		
		
		if (!empty($this->viewMode)) {		  
		  	$part .= '\viewkind'.($this->viewMode).' ';
		}
		
		if (!empty($this->zoomKind)) {	     
			$part .= '\viewzk'.$this->zoomKind.' '; 
		}
			    
		if (!empty($this->zoom)) {	     
			$part .= '\viewscale'.$this->zoom.' '; 
		}
		
		if (!empty($this->sections[0]) && !empty($this->sections[0]->bordered)) {
			$content .= $this->sections[0]->bordered->getContent($this, '\pg');		
		} else if (!empty($this->bordered)) {	
			$part .= $this->bordered->getContent($this, '\pg');	    	    	    
		}
				
		//headers and footers properties
		$part .= !empty($this->oddEvenDifferent) ? '\facingp ' : '';
		$part .= !empty($this->titlepg) ? '\titlepg ' : '';			
		
		//headers and footers if there are no sections
		if (count($this->sections) == 0) {
		  	foreach ($this->headers as $value) {		  
			  	$part .= $value->getContent();
			}
			
			foreach ($this->footers as $value) {		  
			  	$part .= $value->getContent();
			}
		}
		
		//sections	    
		foreach($this->sections as $key => $section) {		  
		  	$part .= $section->getContent();    						
		}			    
		
		$this->content = '{\rtf\ansi\deff0 \r\n';
		$this->content .= $this->getFontTable();
		$this->content .= $this->getColorTable();
		$this->content .= $part.'}';
	}	   
}
?>
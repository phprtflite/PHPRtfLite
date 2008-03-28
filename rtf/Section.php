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

/**
 * Class for creating sections of document.
 * @package Rtf
 * @todo Exception, then columns widths override paper width.
 */
class Section extends Container {
        
	/**#@+ 
	 * Internal use.
	 * @access public
	 */ 	
	var $bordered;
	  
	var $first = false;	
	
	var $alignment;
	
	var $oddEvenDifferent;  	
	/**#@-*/	
	
	/**#@+ 	 
	 * @access private
	 */ 
	var $columnCount = 1;
	
	var $columnsWidths;
	
	var $noBreak = false;
	
	var $lineBetweenColumns = false;
	
	var $spaceBetweenColumns;
	 
	var $paperWidth;  	
	
	var $paperHeight;
	  	
	var $marginLeft;
	
	var $marginRight;
	
	var $marginTop;
	
	var $marginBottom;
	
	var $gutter;
	  	
	var $mirrorMargins;	
	/**#@-*/	
		
	/**
     * Sets the paper width of pages in section.
     * @access public
     * @param float $paperWidth Paper width
     */  	
  	function setPaperWidth($paperWidth) {	    
	    $this->paperWidth = $paperWidth;
	}
	
	/**
     * Sets the paper height of pages in section.   
     * @access public
     * @param float $paperHeight Paper height
     */  	
  	function setPaperHeight($paperHeight) {	    
	    $this->paperHeight = $paperHeight;
	}
	
	/**
     * Sets the margins of pages in section.    
     * @access public
     * @param float $marginLeft Margin left
     * @param float $marginTop Margin top
     * @param float $marginRight Margin right
     * @param float $marginBottom Margin bottom
     */
	function setMargins($marginLeft, $marginTop, $marginRight, $marginBottom) {	  
		$this->marginLeft = $marginLeft;  
		$this->marginTop = $marginTop;
		$this->marginRight = $marginRight;
		$this->marginBottom = $marginBottom;
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
     * Sets the margin definitions on left and right pages.    
     * Notice: OpenOficce doesn't Understant.
     * @access public
     */  	
  	function setMirrorMargins() {	    
	    $this->mirrorMargins = true;
	}	
	
	/**
	 * Gets width of page layout.
	 * @return float
	 * @access public
	 */
	function getLayoutWidth() {
		$paperWidth = !empty($this->paperWidth) ? $this->paperWidth : $this->rtf->paperWidth;
		$marginLeft = !empty($this->marginLeft) ? $this->marginLeft : $this->rtf->marginLeft;
		$marginRight = !empty($this->marginRight) ? $this->marginRight : $this->rtf->marginRight;
	  	
		return ($paperWidth - $marginLeft - $marginRight);
	}
	
	/**
     * Sets borders of section pages.
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
	 * Sets number of columns in section.
	 * @access public
	 * @param integer $columnsCount Number of columns
	 */
	function setColumnsCount($columnsCount) {	  
	  	$this->columnsCount = $columnsCount;
	  	unSet($this->columnsWidths);
	}
	
	/**
	 * Sets space (width) between columns.
	 * @access public
	 * @param float $spaceBetweenColumns Space between columns
	 */
	function setSpaceBetweenColumns($spaceBetweenColumns) {		
		$this->spaceBetweenColumns = $spaceBetweenColumns;
	}
	
	/**
	 * Sets section columns with different widths. <br>
	 * If you use this function, you shouldn't use {@see SetColumnsNumber}.
	 * @access public
	 * @param array $columnsWidths Array with columns widths
	 * @todo Check if columns width sum doesn't overload paper width.
	 */
	function setColumns($columnsWidths) {	  
	  	if (is_array($columnsWidths)) {		    
			$this->columnsCount = count($columnsWidths);
			$this->columnsWidths = $columnsWidths; 
		}
	}
	
	/**
	 * Sets no section break.
	 * If footnotes are use in different sections, Word always will break sections.
	 * @access public
	 */
	function setNoBreak() {	  
	  	$this->noBreak = true;
	}
	
	/**
	 * Sets line between columns.
	 * @access public	
	 */
	function setLineBetweenColumns() {	  
	 	$this->lineBetweenColumns = true;	
	}		 
		
	/**
	 * Sets vertical alignment of text of section.
	 * @param string $alignment Possible values: <br>
	 * 'top' => top (default)<br>
	 * 'center' => center <br>
	 * 'bottom' => bottom <br>
	 * 'justify' => justify	<br>
	 * @todo bottom justify don't work
	 * @accces public
	 */
	function setVerticalAlignment($alignment) {	  
	  	switch ($alignment) {		    
		    default: 		    
		    	$this->alignment = "\vertalt ";
		    break;
		    
		    case 'center':		    	
		    	$this->alignment = "\vertalc ";
		    break;
		    
		    case 'bottom':		    	
		    	$this->alignment = "\vertalb ";
		    break;		    
		    
		    case 'justify':		    	
		    	$this->alignment = "\vertalj ";
		    break;
		}
	}
		
	/**
	 * Creates header for section pages.
	 * @param $type Possible values: <br>
	 * 'all' => all pages (different odd and even headers/footers must be not set) <br>
	 * 'left' => left pages (different odd and even headers/footers must be set) <br>
	 * 'right' => right pages (different odd and even headers/footers must be set) 	<br>
	 * 'first' => first page  	 
	 * @access public
	 * @return Header
	 */
	function &addHeader($type = 'all') {
	  	if (empty($this->rtf->oddEvenDifferent) && $type == 'all') {
		    $header = new Header($this->rtf, $type);
		} else if (!empty($this->rtf->oddEvenDifferent) 
						&& ($type == 'left' || $type == 'right')) {		  
		  	$header = new Header($this->rtf, $type);	
		} else if ($type == 'first') {
		  	$header = new Header($this->rtf, $type);	
		  	$this->titlepg = 1;
		} else {			
		  	return;
		}		 

		$this->headers[$type] = &$header;
		return $header;		
	} 	
	
	/**
	 * Creates footer for section pages.
	 * @param $type Possible values: <br>
	 * 'all' => all pages (different odd and even headers/footers must be not set) <br>
	 * 'left' => left pages (different odd and even headers/footers must be set) <br>
	 * 'right' => right pages (different odd and even headers/footers must be set) 	<br>
	 * 'first' => first page  	 
	 * @access public
	 * @return Footer
	 */
	function &addFooter($type = 'all') {
	  	if (empty($this->rtf->oddEvenDifferent) && $type == 'all') {
		    $footer = new Footer($this->rtf, $type);
		} else if (!empty($this->rtf->oddEvenDifferent) 
						&& ($type == 'left' || $type == 'right')) {		  
		  	$footer = new Footer($this->rtf, $type);	
		} else if ($type == 'first') {
		  	$footer = new Footer($this->rtf, $type);	
		  	$this->titlepg = 1;
		} else {				
		  	return;
		}		 

		$this->footers[$type] = &$footer;
		return $footer;		
	} 
	
	/**
	 * Breaks page.
	 * @since 0.2.0/ This method is used instead of using "page" tag in Container::writeText method.
	 * @?ccess public.
	 */
	function insertPageBreak() {
		$this->elements[] = "\\page";
	}
		
	/** 
	 * Gets rtf code of section. Internal use.
	 * @return string
	 * @access public
	 */
	function getContent() {	  
	  	$content = '';
	  		  	
	  	if (empty($this->first)) {	
			$content .= '\sect \sectd ';
		}		
				
		//headers
		if (!empty($this->headers)) {
			foreach ($this->headers as $value) {		  
			  	$content .= $value->getContent();
			}
		} else {
		  	foreach ($this->rtf->headers as $value) {		  
			  	$content .= $value->getContent();
			}
		}
		
		//footers
		if (!empty($this->footers)) {
			foreach ($this->footers as $value) {		  
			  	$content .= $value->getContent();
			}
		} else {
		  	foreach ($this->rtf->footers as $value) {		  
			  	$content .= $value->getContent();
			}
		}
		
		//borders
		if (!empty($this->bordered)) {
			$content .= $this->bordered->getContent($this->rtf, '\pg');
		} else if (!empty($this->rtf->bordered)) {	
			$content .= $this->rtf->bordered->getContent($this->rtf, '\pg');
		}		
	  	
	  	//section properties
	  	if (!empty($this->noBreak)) {		
			$content .= '\sbknone '; 	
		}
	  	
	  	if (!empty($this->columnsCount)) {		    
		 	$content .= '\cols'.$this->columnsCount.' '; 
		}
		
		if (empty($this->columnsWidths)) {				
			if (!empty($this->spaceBetweenColumns)) {			  
			  	$content .= '\colsx'.round($this->spaceBetweenColumns * TWIPS_IN_CM).' ';
			}			
		} else {		  
		  	$width = 0;
		  	foreach ($this->columnsWidths as $value) {		  	  
		  	  	$width += $value * TWIPS_IN_CM;
		  	}
		  	
		  	$printableWidth = ($this->rtf->paperWidth - $this->rtf->marginLeft - $this->rtf->marginRight);		  	
		  	$space = round(($printableWidth * TWIPS_IN_CM - $width) / (count($this->columnsWidths) - 1));
		  			  	
			$i = 1;
		  	foreach ($this->columnsWidths as $key => $value) {
				$content .= '\colno'.$i.'\colw'.($value * TWIPS_IN_CM);				
				if (!empty($this->columnsWidths[$key])) {				  
				 	$content .= '\colsr'.$space;
				}
			  	$i ++;
			}		  
			$content .= ' ';
		}
					
		if (!empty($this->lineBetweenColumns)) {		  
		  	$content .= '\linebetcol ';
		}
				
		/*---Page part---*/				
		if (isSet($this->paperWidth)) {		  
		  	$content .= '\pgwsxn'.round($this->paperWidth * TWIPS_IN_CM).' ';
		}
		
		if (isSet($this->paperHeight)) {		  
		  	$content .= '\pghsxn'.round($this->paperHeight * TWIPS_IN_CM).' ';
		} 
		
		if (isSet($this->marginLeft)) {		  
		  	$content .= '\marglsxn'.round($this->marginLeft * TWIPS_IN_CM).' ';
		} 
		
		if (isSet($this->marginRight)) {		  
		  	$content .= '\margrsxn'.round($this->marginRight * TWIPS_IN_CM).' ';
		}
		
		if (isSet($this->marginTop)) {		  
		  	$content .= '\margtsxn'.round($this->marginTop * TWIPS_IN_CM).' ';
		}
		
		if (isSet($this->marginBottom)) {		  
		  	$content .= '\margbsxn'.round($this->marginBottom * TWIPS_IN_CM ).' ';
		}
		
		if (isSet($this->gutter)) {		  	
			$content .= '\guttersxn'.round($this->gutter * TWIPS_IN_CM).' '; 
		}
		
		if (!empty($this->mirrorMargins)) {		  	
			$content .= '\margmirsxn '; 
		}				
		
		//vertical alignment				
		if (!empty($this->alignment)) {		  
		  	$content .= $this->alignment;	  
		}			
		
	  	$content .= "\r\n".parent::getContent()."\r\n";	  	
	  	
	  	return $content;
	}  
}
?>
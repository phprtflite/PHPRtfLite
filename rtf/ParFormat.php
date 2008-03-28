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
 * Paragraph formating class.
 * @package Rtf
 */
class ParFormat {

	/**#@+ @access private */
	var $alignment = '';
	
	var $indentFirst;
	
	var $indentLeft;
	
	var $indentRight;
		
	var $spaceBefore;
	
	var $spaceAfter;
	
	var $spaceBetweenLines;
	
	var $shading;
	
	var $backColor;	
	
	var $bordered;
	/**#@-*/
	
	/**
	 * ParFormat Constructor
	 * @param string $alignment. Possible values: <br>
	 * 'left'- left alignment <br>
	 * 'right'- right alignment <br>
	 * 'center'- centera lignment <br>
	 * 'justify'- justify alignment
	 * @access public	 
	 */
	function ParFormat($alignment = 'left') {		
		switch ($alignment) {			
			case 'left':			
				$this->alignment = '\ql';
			break;	
			
			case 'right':			
				$this->alignment = '\qr';
			break;
			
			case 'center':			
				$this->alignment = '\qc';
			break;
			
			case 'justify':			
				$this->alignment = '\qj';
			break;		
			
			default:
			
			break;
		}
	}
	  
  	/**
  	 * Sets first line indent (default 0)
  	 * @param float $indentFirst
  	 * @access public
	 */	
	function setIndentFirst($indentFirst) {	  
	  	$this->indentFirst = round($indentFirst * TWIPS_IN_CM);
	}

  	/**
  	 * Sets left indent (default 0)
  	 * @param float $indentLeft
  	 * @access public
	 */		
	function setIndentLeft($indentLeft) {	  
	  	$this->indentLeft = round($indentLeft * TWIPS_IN_CM);
	}
	
	/**
  	 * Sets right indent (default 0)
  	 * @param float $indentRight
  	 * @access public
	 */	
	function setIndentRight($indentRight) {	  
	  	$this->indentRight = round($indentRight * TWIPS_IN_CM);
	}
	
	/**
	 * Sets the vertical spacing before this paragraph.     
	 * @param integer $spaceBefore Space before
	 * @access public
	 */
	function setSpaceBefore($spaceBefore) {	  
	  	$this->spaceBefore = round($spaceBefore * SPACE_IN_POINTS);
	}
	
	/**
     * Sets the vertical spacing after this paragraph.    
     * @param integer $spaceAfter Space after
     * @access public
     * @todo documentation
     */
	function setSpaceAfter($spaceAfter) {	  
	  	$this->spaceAfter = round($spaceAfter * SPACE_IN_POINTS);
	}
  	
  	/**
     * Sets the vertical spacing between paragraph lines.    
     * @param integer $spaceBetweenLines Vertical space between lines     
     * @access public
     * @todo documentation
     */
  	function setSpaceBetweenLines($spaceBetweenLines) {	  
	  	$this->spaceBetweenLines = round($spaceBetweenLines * SPACE_IN_LINES);
	}

    /**
     * Sets shading.
     * @param integer $shading Shading value in percents (from 0 till 100)
     * @access public
     * 
     */
	function setShading($shading) {	
		$this->shading = $shading * 100;		
	}

	/**
	 * Sets background color.
	 * @param string $backColor
	 * @access public
	 */
  	function setBackColor($backColor) {
		$this->backColor = Util::formatColor($backColor);			
	}
	
	/**
     * Sets borders of element.    
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
	 * Gets rtf code of paragraph format. Internal use. 
	 * @param Rtf $rtf Rtf object
	 * @return string
	 * @access public 
	 */
	function getContent(&$rtf) {	     
		$content = '';   
		
		if (!empty($this->alignment)) {		  
		  	$content .= $this->alignment.' ';
		}
		
		if (!empty($this->indentFirst)) {		  
		  	$content .= '\fi'.$this->indentFirst.' ';
		}
		
		if (!empty($this->indentLeft)) {		  
		  	$content .= '\li'.$this->indentLeft.' ';
		}		 
		
		if (!empty($this->indentRight)) {		  
		  	$content .= '\ri'.$this->indentRight.' ';
		}
		
		if (!empty($this->spaceBefore)) {		  
		  	$content .= '\sb'.$this->spaceBefore.' ';		
		}		
		
		if (!empty($this->spaceAfter)) {		  
		  	$content .= '\sa'.$this->spaceAfter.' ';		
		}
		
		if (!empty($this->spaceBetweenLines)) {		  
		  	$content .= '\sl'.$this->spaceBetweenLines.' ';		
		}
		
		if (!empty($this->bordered)) {	
			$content .= $this->bordered->getContent($rtf, '\\');
		}
		
		if (!empty($this->shading)) {		  
		  	$content .= '\shading'.$this->shading.' ';		
		}
		
		if (!empty($this->backColor)) {		  
		  	$rtf->addColor($this->backColor);					  
		  	$content .= '\cbpat'.$rtf->GetColor($this->backColor).' ';		
		}		
			
		return $content;
	}
}
?>
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
* Text font formating class.
* @package Rtf
*/
class Font {

    /**#@+ @access private */
    var $size;

    var $font;

    var $fontColor;

    var $backColor;
   
    var $bold;
    
    var $italic;
    
    var $underline;
    
    var $strike;
    
    var $strikeDouble;
    
    var $animatedText;
    /**#@-*/
        
    /**
     * Font constructor
     * @param int $size Font size
     * @param string $font Font (etc. "Times new Roman", "Arial" and other)
     * @param string Font color
     * @param string Background color of font
     */
    function Font($size = 10, $font = '', $fontColor = '', $backColor = '') {      
      	$this->size = $size;
		$this->font = $font;
		$this->fontColor = Util::formatColor($fontColor);
		$this->backColor = Util::formatColor($backColor);	  
	}
	
	/**
	 * Sets text bold.
	 * @access public
	 */
	function setBold() {	  
	  	$this->bold = 1;
	}
	
	/**
	 * Sets text italic.
	 * @access public
	 */
	function setItalic() {	  
	  	$this->italic = 1;
	}
	
	/**
	 * Sets text underline.
	 * @access public
	 */
	function setUnderline() {	  
	  	$this->underline = 1;
	}
	
	/**
	 * Sets strikethrough of text.
	 * @param $strike If 1 then single, if 2 then double
	 * @access public
	 */
	function setStrike($strike = 1) {		
		if ($strike == 1) {	
			$this->strike = 1;
		} else if ($strike == 2) {			
			$this->strikeDouble = 1;
		}
	}
		
	
	/**
     * Sets animated text properties.     
     * @param integer $animatedText Animated Text Properties. Possible values: <br>
	 * '1' => Las Vegas Lights,  <br>
	 * '2' => Blinking background, <br>
	 * '3' => Sparkle text, <br>
	 * '4' => Marching black ants, <br>
	 * '5' => Marching red ants, <br>
	 * '6' => Shimmer
	 * @access public	
     */ 
	function setAnimatedText($animatedText) {	  
	  	$this->animatedText = $animatedText;
	}
			
	/** 
	 * Gets rtf code of font. Internal use.
	 * @param Rtf $rtf Rtf object
	 * @return string
	 * @access public
	 */
	function getContent(&$rtf) {	
		$content = !empty($this->size) ? '\fs'.($this->size * 2).' ' : '';		
		
		if (!empty($this->font)) {		  
		  	$rtf->addFont($this->font);
			$content .= $rtf->GetFont($this->font).' ';		  	  
		}
		
		if (!empty($this->fontColor)) {			
			$rtf->addColor($this->fontColor); 
		  	$content .= $rtf->GetFontColor($this->fontColor).' ';
		}
		
		if (!empty($this->backColor)) {			  
			$rtf->addColor($this->backColor); 
		  	$content .= $rtf->GetBackColor($this->backColor).' ';
		}

		$content .= !empty($this->bold) ? '\b ' : '';		
		$content .= !empty($this->italic) ? '\i ' : '';		
		$content .= !empty($this->underline) ? '\ul ' : '';		
		$content .= !empty($this->animatedText) ? '\animtext'.$this->animatedText : '';
		$content .= !empty($this->strike) ? '\strike '.$this->animatedText : '';
		$content .= !empty($this->strikeDouble) ? '\striked1 '.$this->animatedText : '';
		
		return $content;
	}  
}
?>
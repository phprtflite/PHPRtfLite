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
 * Atstract class of container.
 * @abstract
 * @package Rtf
 */
class Container {
    
    /**#@+ 
	 * Internal use.
	 * @access public
	 */ 
    var $rtf;
  	  	
  	var $elements = array();  
  	
  	var $pard = '\pard ';
  	
  	var $emptyPar = false;
  	/**#@-*/
	   	
  	function Container(&$rtf) {	    
	    $this->rtf = &$rtf;	
	}
    
	/**
	 * Writes rtf code to container. 	 
  	 * @param string $text
  	 * @access public
  	 */
  	function writeRtfCode($text) {	    
	    $this->elements[] = $text;
	}
	
	/**
	 * Adds empty paragraph to container.
	 * @param Fonf $font Font of text.
	 * @param ParFormat $parFormat Paragraph format.
	 */
	function emptyParagraph(&$font, &$parFormat) {
		if (is_a($parFormat, 'ParFormat') && is_a($font, 'Font')) {		  	
		  	$content = (count($this->elements) != 0 && empty($this->emptyPar)) ? '\par ' : '';	
			$content .= $this->pard.$parFormat->getContent($this->rtf);  			
			$content .=	'{'.$font->getContent($this->rtf).' \par}'."\r\n";  
			$this->elements[] = $content;					
			$this->emptyPar = true;
		}		
	}
	
	/**
	 * Writes text to container. 	 
  	 * @param string $text Text. Also you can use html style tags. Possible tags:<br>
  	 * strong, b- bold; <br>
	 * em - ; <br>
	 * i - italic; <br>
	 * u - underline; <br>
	 * br - line break; <br>
	 * chdate - current date; <br>
	 * chdpl - current date in long format; <br>
	 * chdpa - current date in abbreviated format; <br>
	 * chtime - current time; <br>
	 * chpgn, pagenum - page number ; <br>
	 * tab - tab
	 * sectnum - section number; <br>
	 * line - line break; <br>
	 * page - page break; <br>
	 * sect - section break; <br>
  	 * @param Font $font Font of text.
  	 * @param mix $parFormat Paragraph format. Use ParFormat object or null object. If null object, text is written in the same paragraph.
  	 * @param bool $replaceTags If false, then html style tags are not replaced with rtf code.  	 
  	 * @access public	
  	 * @todo Documentation
	 */
	function writeText($text, &$font, &$parFormat, $replaceTags = true) {				
		$text = str_replace('\\', '\\\\', $text);
		$text = str_replace("\r\n", "\r\n".'\par ', $text);
		
		if (!empty($replaceTags)) {
			//bold		
			$text = preg_replace("/<STRONG[ ]*>(.*?)<\/STRONG[ ]*>/mi", "\\b \\1\\b0 ", $text);
			$text = preg_replace("/<B[ ]*>(.*?)<\/B[ ]*>/mi", "\\b \\1\\b0 ", $text);		
			//italic
			$text = preg_replace("/<EM[ ]*>(.*?)<\/EM[ ]*>/mi", "\\i \\1\\i0 ", $text);
			$text = preg_replace("/<I[ ]*>(.*?)<\/I[ ]*>/mi", "\\i \\1\\i0 ", $text);		
			//underline
			$text = preg_replace("/<U[ ]*>(.*?)<\/U[ ]*>/mi", "\\ul \\1\\ul0 ", $text);		
			//break
			$text = preg_replace("/<BR[ ]*(\/)?[ ]*>/mi", "\\line ", $text);
			$text = preg_replace("/<CHDATE[ ]*(\/)?[ ]*>/mi", "\\chdate ", $text);
			$text = preg_replace("/<CHDPL[ ]*(\/)?[ ]*>/mi", "\\\chdpl ", $text);
			$text = preg_replace("/<CHDPA[ ]*(\/)?[ ]*>/mi", "\\chdpa ", $text);
			$text = preg_replace("/<CHTIME[ ]*(\/)?[ ]*>/mi", "\\chtime ", $text);
			$text = preg_replace("/<CHPGN[ ]*(\/)?[ ]*>/mi", "\\chpgn ", $text);
			
			$text = preg_replace("/<TAB[ ]*(\/)?[ ]*>/mi", "\\tab ", $text);
			$text = preg_replace("/<BULLET[ ]*(\/)?[ ]*>/mi", "\\bullet ", $text);
			
			$text = preg_replace("/<PAGENUM[ ]*(\/)?[ ]*>/mi", "\\chpgn ", $text);
			$text = preg_replace("/<SECTNUM[ ]*(\/)?[ ]*>/mi", "\\sectnum ", $text);
			
			$text = preg_replace("/<LINE[ ]*(\/)?[ ]*>/mi", "\\line ", $text);
			//$text = preg_replace("/<PAGE[ ]*(\/)?[ ]*>/mi", "\\page ", $text);
			//$text = preg_replace("/<SECT[ ]*(\/)?[ ]*>/mi", "\\sect", $text);	
		}	
					
		$text = Util::utf8Unicode($text);		
		//content formating
		$content = (is_a($parFormat, 'ParFormat') && count($this->elements) != 0 && empty($this->emptyPar)) ? '\par ' : '';	
		$this->emptyPar = false;  	
		$content .= is_a($parFormat, 'ParFormat') ? $this->pard.$parFormat->getContent($this->rtf) : '';	 					
		$content .=	'{';
		if (is_a($font, 'Font')) {
			$content .= $font->getContent($this->rtf);			
		}				
		$content .= $text.'}'."\r\n";
				
		$this->elements[] = $content;		
	}

	/**
	 * Writes hyperlink to container.
	 * @param string $hyperlink Hyperlink url (etc. "http://www.phprtf.com")
	 * @param string $text Hyperlinks text If false, hyperlink is writen in previous paragraph format.
	 * @param Font $font Font
	 * @param mix $parFormat Paragraph format or null object. If null object hyperlink is written in the same paragraph.	 
	 * @access public
	 */	
	function writeHyperLink($hyperlink, $text, &$font, &$parFormat) {	 
	  	$content = (is_a($parFormat, 'ParFormat') && count($this->elements) != 0) && empty($this->emptyPar) ? '\par ' : '';		
	  	$this->emptyPar = false;  
		$content .= is_a($parFormat, 'ParFormat') ? $this->pard.$parFormat->getContent($this->rtf) : '';
	   	
		$this->elements[] =	$content.'{\field {\*\fldinst {HYPERLINK "'.$hyperlink.'"}}{\\fldrslt {';
		$null = null;
		$this->writeText($text, $font, $null);
		$this->elements[] .= '}}}'."\r\n";	
		
	}
		
	/**
	 * Adds table to element container.
	 * @param string $alignment Alingment of table. Possible values: 'left', 'center', 'right'
	 * @return Table 
	 * @access public
	 */
	function &addTable($alignment = 'left') {	    
		$this->emptyPar = false;  
	    $table = new Table($this, $alignment);
	    $this->elements[] = &$table;	    	    
	    return $table;
	}
	
	/**
	 * Adds image to element container.
	 * @param string $fileName Name of image file.
   	 * @param mix $parFormat Paragraph format or null object. If null object immage is in the same paragraph.
	 * @param float $width Default 0. If 0 image is displayed by it's height.
	 * @param float $height Default 0. If 0 image is displayed by it' width. If boths parameters are 0, image is displayed as is.	 
	 * @return Image
	 * @access public
	 */	 
	function &addImage($fileName, &$parFormat, $width = 0, $height = 0) {	
		$this->emptyPar = false;  	
		$image = new Image($this->rtf, $fileName, $parFormat, $width, $height);		
		$this->elements[] = &$image;		
		return $image;
	}
		
	/** 
	 * Gets rtf code of Container. Internal use.
	 * @return string
	 * @access public
	 */
	function getContent() {	  
	  	$content = '';
	  
		foreach($this->elements as $key => $value) {			
			if (is_string($value)) {	
				$content .= $value;    			
			} else {											
				if ($key != 0 
					&& is_a($value, 'Table') 
					&& !is_a($this->elements[$key - 1], 'Table')) 
				{				  	
				  	$content .= '\par';
				} else if (is_a($value, 'Image')) {	
					if (is_a($value->parFormat, 'ParFormat')) {
					 	$content .= $key != 0 ? '\par' : '';
					 	$content .= $this->pard.$value->parFormat->getContent($this->rtf);
					}
				}				
								
				$content .= $value->getContent();			
			} 			
		}	 
		
		return $content;
	}    
}
?>
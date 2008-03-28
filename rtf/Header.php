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
 * Class for creating headers of documents.
 * @package Rtf
 */
class Header extends Container {

	/**#@+ @access private */  
	var $type;	
	
	var $headery;
	/**#@-*/  
  	
  	/**
  	 * Constructor. Internal use.
  	 * @param Rtf &$rtf
  	 * @param string $type
  	 * @access public
  	 */
  	function Header(&$rtf, $type) {	    
	    $this->rtf = &$rtf;	
	    
	    switch ($type) {		  
			case 'all': 			
				$this->type = 'header'; 
			break;
			
			case 'left': 
				$this->type = 'headerl'; 
			break;
			
			case 'right':			
				$this->type = 'headerr'; 
			break;
			
			case 'first':			
				$this->type = 'headerf'; 
			break;
		}	    
	}
	
	/**
	 * Sets vertical header position from top of page.
	 * @param float $height
	 * @access public 
	 */
	function setPosition($height) {	  
	  	$this->headery = $height;
	}
  	  	
	/**
	 * Gets rtf code of header. Internal use. 
	 * @return string
	 * @access public 
	 */	
	function getContent() {	  
	  	$content = isSet($this->headery) ? '\headery'.round(TWIPS_IN_CM * $this->headery).' ' : '';	  
		$content .= '{\\'.$this->type.' ';						
		$content .= parent::getContent();
		$content .= '\par ';
		$content .= '}';
		return $content."\r\n";
	}			
}

?>
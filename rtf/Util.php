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
 * Utilities class.
 * @package Rtf
 */
class Util {	
	  
	/**
	 * Formats color code. Internal use.
	 * @param string $color Color
	 * @access public
	 * @static
	 */
	function formatColor($color) {	  
	  	if (strlen($color) == 7 & substr($color, 0, 1) == '#') {		    	    
		    return '\red'.hexdec(substr($color, 1, 2)).'\green'.hexdec(substr($color, 3, 2)).'\blue'.hexdec(substr($color, 5, 2));
		}		
	  	return $color;		  
	}
	
	/**
	 * Formats utf-8 encoded text. Internal use.
	 * @param string $str Text
	 * @access public
	 * @static	 
	 */	
	function utf8Unicode($str) {
	  	return Util::unicodeToEntitiesPreservingAscii(Util::utf8ToUnicode($str));
	}
	
	/**
	* @see http://www.randomchaos.com/documents/?source=php_and_unicode
	* @access private
	* @static
	*/
	function utf8ToUnicode($str) {        
	    $unicode = array();        
	    $values = array();
	    $lookingFor = 1;    
	    
	    for ($i = 0; $i < strlen($str); $i++ ) {
	        $thisValue = ord($str[$i]);        
	    
		    if ($thisValue < 128) {
				$unicode[] = $thisValue;
	        } else {        
	            if ( count( $values ) == 0 ) {			
					$lookingFor = ( $thisValue < 224 ) ? 2 : 3;
				}
	            
	            $values[] = $thisValue;
	            
	            if ( count( $values ) == $lookingFor ) {        
	                $number = ( $lookingFor == 3 ) ?
	                    ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
	                	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
	                    
	                $unicode[] = $number;
	                $values = array();
	                $lookingFor = 1;        
	            }         
	        }         
	    } 
	
	    return $unicode;	
	} 
	
	/** 
	 * @access private
	 * @static
	 */
	function unicodeToEntities($unicode) {        
	    $entities = '';    
		foreach( $unicode as $value )  {	
			$entities .= '\uc0\u'.$value.' ';
	    }    
		return $entities;        
	}
	
	/** 
	 * @access private 
	 * @static
	 */
	function unicodeToEntitiesPreservingAscii($unicode) {
	    $entities = '';    
	    foreach( $unicode as $value ) {    	
			if ($value != 65279) {
		        $entities .= ( $value > 127 ) ? '\uc0\u' . $value . ' ' : chr( $value );        
		    }
	    }     
	    return $entities;    
	}
}
?>
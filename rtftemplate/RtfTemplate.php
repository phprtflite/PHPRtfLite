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
 * Class for representing bordered part of object.
 * @package RtfTemplate
 */
require_once('../rtf/Rtf.php');
require_once('Mark.php');

function createMark($matches) {
	$str = $matches[1];
	$str = ereg_replace('\\\\[^ ]* ', '', $str);
	$str = ereg_replace('{', '', $str);
	$str = ereg_replace('}', '', $str);
	$str = ereg_replace('
', '', $str);
	
	return '\' . 
$this->renderMark(\'' . $str . '\') .
\'';
}

/**
 * 
 */
class RtfTemplate {
	
	/**
	 * Template file name.
	 *
	 * @var string
	 */
	var $fileName;
	
	/**
	 * Directory where generated templates are saved.
	 *
	 * @var string
	 */
	var $compileDir;
	
	/**
	 * Array of marks.
	 *
	 * @var array
	 */
	var $marks = array(); 
	
	/**
	 * Enter description here...
	 *
	 * @var int Count of colors used in template.
	 */
	var $colorsCount;
	
	/**
	 * 
	 *
	 * @var unknown_type
	 */
	var $fontsCount;
	
	/**
	 * This Rtf object is used just for saving information about template colors, fonts and styles.
	 * Then this information is used in creting colors table and other information.
	 *
	 * @var Rtf
	 */
	var $rtf;
	
	/**
	 * Constructs template object. Saves generated template in $compileDir.
	 *
	 * @param string $fileName
	 * @param string $compileDir
	 * @return RtfTemplate
	 * @access public
	 */
	function RtfTemplate($fileName, $compileDir) {
		$this->fileName = $fileName;
		$this->compileDir = $compileDir;
		
		$this->rtf = new Rtf();
		$this->prepare();	
	}
	
	/**
	 * 
	 *
	 * @param string $name
	 * @return Container
	 * @access public
	 */
	function &createMark($name) {
		$this->marks[$name] = new Mark($this->rtf);
		return $this->marks[$name];
	}
	
	/**
	 * 
	 *
	 * @param string $name
	 * @return Mark
	 * @access public
	 */
	function getMark($name) {
		if (!empty($this->marks[$name])) {
			return $this->marks[$name];
		}
		
		return null;
	}
	
	/**
	 * 
	 *
	 * @param string $name
	 * @return string
	 */
	function renderMark($name) {
		$mark = $this->getMark($name);
		
		if ($mark != null) {
			return $mark->getContent();// . ' \par \pard ';
		} else {
			return '';		
		}
	}
	
	/**
	 * Creates part of colors table content.
	 *
	 * @return string
	 */
	function getColorsContent() {		
		$part = "\r\n";
		$i = 0;
	    foreach ($this->rtf->colors as $key => $value) {	    			  
		  	if ($i >= $this->colorsCount) {
	    		$part .= $key.';';
	    	}
	    	
	    	$i ++;	
		}
		$part .= "\r\n";				    
		return $part;
	}
	
	/**
	 * Creates part of colors table content.
	 *
	 * @return string
	 */
	function getFontsContent() {		
		$part = "\r\n";
		$i = 0;
	    foreach ($this->rtf->fonts as $key => $value) {	    			  
		  	if ($i >= $this->fontsCount) {		  			  
		  		$part .= '{'.$value.' '.$key.';}';		
	    	}
	    	
	    	$i ++;	
		}
		$part .= "\r\n";				    
		return $part;
	}
	
	/**
	 * 
	 * @param string $content
	 * @return string
	 */
	function prepareColors($content) {		
		$startColors = strpos($content, '{\colortbl;');		
		$colorsPart = substr($content, $startColors);		
		$endColors = strpos($colorsPart, '}');
		$colorsPart = substr($colorsPart, 0, $endColors);
		$endColors += $startColors; 
		
		$colorsArr = split('red', $colorsPart);
		
		$this->colorsCount = count($colorsArr) - 1;
				
		for ($i = 0; $i < $this->colorsCount; $i++) {
			$this->rtf->addColor($i);
		}
		
		$content = substr($content, 0, $endColors) . '\' . $this->getColorsContent() . \''. substr($content, $endColors);
		return $content;
	}
	
	/**
	 * 
	 *
	 * @param string $content
	 * @return string
	 */
	function prepareFonts($content) {
		$start = strpos($content, '{\\fonttbl');		
		$part = substr($content, $start);		
		$end = strpos($part, '{\colortbl');
		$part = substr($part, 0, $end);
		$end += $start;
		
		$arr = split(';\}\{\\\\f', $part);
		
		$last = $arr[count($arr) - 1];
		$pos = strpos($last, '\\');
		$this->fontsCount = substr($last, 0, $pos) + 1;

		for ($i = 0; $i < $this->fontsCount; $i++) {
			$this->rtf->addFont($i);
		}
		
		$content = substr($content, 0, $end - 1) . '\' . $this->getFontsContent() . \'' . substr($content, $end - 1);
		return $content;	
	}
	
	/**
	 * 
	 *
	 */
	function prepare() {
		$content = file_get_contents($this->fileName);	

		// Removing not needed information		
		$content = ereg_replace('\\\\insrsid[0-9]+', '', $content);
		$content = ereg_replace('\\\\pararsid[0-9]+', '', $content);
		$content = ereg_replace('\\\\charrsid[0-9]+', '', $content);
		$content = ereg_replace('\\\\rsid[0-9]+', '', $content);
				
		$content = $this->prepareColors($content);
		$content = $this->prepareFonts($content);
				
		// Marks part
		$content = preg_replace_callback('/\$<([^>]*)>/', 'createMark', $content);			
				
		// Creating content
		$content = '<?php $tmp = \'' . $content . '\';?>';		
		
		// Writing to file
		$handle = fopen($this->compileDir . '/template.php', 'w');
		fwrite($handle, $content);
		fclose($handle);
    }
   
	
	function sendRtf() {		
		header('Content-Disposition: attachment; filename=test2.rtf');
		header('Content-type: application/msword'); 
		header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	    header("Pragma: public");	
	    
	    include($this->compileDir . '/template.php');
	    echo $tmp;
	    
	    $handle = fopen($this->compileDir . '/result.rtf', 'w');
		fwrite($handle, $tmp);
		fclose($handle);
	}
}

?>
<?php

class RtfTemplate {
	
	var $fileName;
	
	var $compileDir;
	
	var $marks = array(); 
	
	var $colorsCount;
	
	/**
	 * 
	 *
	 * @var Rtf
	 */
	var $rtf;
	
	function RtfTemplate($fileName, $compileDir) {
		$this->fileName = $fileName;
		$this->compileDir = $compileDir;
		
		$this->rtf = new Rtf();
		$this->prepare();	
	}
	
	/**
	 * 
	 *
	 * @param unknown_type $name
	 * @return Container
	 */
	function &createMark($name) {
		$this->marks[$name] = new Container($this->rtf);
		return $this->marks[$name];
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $name
	 * @return Container
	 */
	function getMark($name) {
		if (!empty($this->marks[$name])) {
			return $this->marks[$name];
		}
		
		return null;
	}
	
	function renderMark($name) {
		$mark = $this->getMark($name);
		
		if ($mark != null) {
			return $mark->getContent() . ' \par \pard ';
		} else {
			return '';		
		}
	}
	
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
	
	function prepare() {
		$content = file_get_contents($this->fileName);	

		// Removing not needed information
		$content = ereg_replace('\\rsid[0-9]*', '', $content);
		$content = ereg_replace('\\insrsid[0-9]*', '', $content);
		$content = ereg_replace('\\pararsid[0-9]*', '', $content);
		$content = ereg_replace('\\charrsid[0-9]*', '', $content);
		
		// Colors part
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
		
		$content = substr($content, 0, $endColors) . '" . $this->getColorsContent() . "'. substr($content, $endColors);
				
		$content = ereg_replace('\$<([^>]*)>', '" . $this->renderMark(\'\1\') . "', $content);
			
		
		$content = '<?php $tmp = "' . str_replace('\\', '\\\\', $content) . '";?>';
		
		
		$handle = fopen($this->compileDir . '/template.php', 'w');
		fwrite($handle, $content);
		fclose($handle);
    }
   
	
	function sendRtf() {		
		header('Content-Disposition: attachment; filename=test.rtf');
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
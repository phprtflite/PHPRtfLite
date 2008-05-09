<?php

class RtfTemplate {
	
	var $fileName;
	
	var $compileDir;
	
	var $marks = array(); 
	
	/**
	 * Enter description here...
	 *
	 * @var Rtf
	 */
	var $rtf;
	
	function RtfTemplate($fileName, $compileDir) {
		$this->fileName = $fileName;
		$this->compileDir = $compileDir;
		
		$this->rtf = new Rtf();
	}
	
	/**
	 * Enter description here...
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
		return $this->marks[$name];
	}
	
	function renderMark($name) {
		$mark = $this->getMark($name);
		echo $mark->getContent();		
	}
	
		
	function prepare() {
		$content = file_get_contents($this->fileName);	

		// Removing not needed information
		$content = ereg_replace('\\rsid[0-9]*', '', $content);
		$content = ereg_replace('\\insrsid[0-9]*', '', $content);
		$content = ereg_replace('\\pararsid[0-9]*', '', $content);
		$content = ereg_replace('\\charrsid[0-9]*', '', $content);
		
		//echo $content;
		$content = ereg_replace('\$<([^>]*)>', '" . $this->renderMark(\'\1\') . "', $content);
			
		
		$content = '<?php global $tmp; $tmp = "' . str_replace('\\', '\\\\', $content) . '";?>';
		
		
		$handle = fopen($this->compileDir . '/template.php', 'w');
		fwrite($handle, $content);
		fclose($handle);
    }
   
	
	function sendRtf() {			
		/*header('Content-Disposition: attachment; filename=test.rtf');
		header('Content-type: application/msword'); 
		header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	    header("Pragma: public");	*/
	    
	    include($this->compileDir . '/template.php');
	    echo $tmp;
	}
}

?>
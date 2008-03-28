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
 * Class for creating cells of tables.
 * @package Rtf
 */
class Cell extends Container {

	/**#@+ 
	 * Internal use.
	 * @access public
	 */   
	var $table;

	var $row;
	
	var $column;
	
	var $elements = array();
		
	var $alignment;
	
	var $text;
	
	var $font;
	
	var $direction;
	
	var $backColor;
	
	var $bordered;	
	
	var $width;
	
	var $horMerged;
	
	var $verMerged;
	
	var $verStart;

	var $pard = '\pard \intbl ';
	/**#@-*/ 

	/**
	 * Constructor of cell.
	 * @param Table &$table Table
	 * @param int $row Row number
	 * @param int $column Column number
	 */
	function Cell(&$table, $row, $column) {	  
	  	$this->table = &$table;
		$this->rtf = &$table->rtf;	  
		$this->row = $row;
		$this->column = $column;
		
		$this->isCell = 1;
	}
	
	/**
	 * Overriden. Does nothing. Nesting cells are not suported in current version.
	 */
	function &addTable($alignment = 'left') {	 

	}
	
	/**
	 * Sets alignment of empty cell. The method writeToCell overrides it with ParFormat alignment.
	 * @param string $alignment Alignment of cell. Possible values:<br>
	 * 'left' => left alignment<br>
	 * 'center' => center alignment<br>
	 * 'right' => right alignment<br>
	 * 'justify' => justify alignment
	 */
	function setDefaultAlignment($alignment = 'left') {
	  	switch ($alignment) {
		    case 'left':
		    	$this->alignment = '\ql';
		    break;
		    
		    case 'center':
		    	$this->alignment = '\qc';
		    break;
		    
		    case 'right':
		    	$this->alignment = '\qr';
		    break;
		    
		    case 'justify':
		    	$this->alignment = '\qj';
		    break;
		}
	}
	
	/**
	 * Sets font of empty cell. The method writeToCell overrides it with another Font.
	 * @param $font Font
	 * @access public
	 */
	function setDefaultFont(&$font) {
		$this->font = &$font;  	
	}
		
	/**
	 * Sets vertical alignment of cell
	 * @param $verticalAlignment Vertical alignment of cell (default top). Possible values:<br>
	 * 'top' => top alignment; <br>
	 * 'center' => center alignment; <br>
	 * 'bottom' => bottom alignment.
	 * @access public
	 */
	function setVerticalAlignment($verticalAlignment = 'top') {	  
	  	switch ($verticalAlignment) {		    
		    case 'top':			
				$this->verticalAlignment = '\clvertalt ';	  		    
		    break;
		    
		    case 'center':
				$this->verticalAlignment = '\clvertalc ';		    
		    break;
		    
		    case 'bottom':		    
				$this->verticalAlignment = '\clvertalb ';
		    break;
		}
	}
	
	/**
	 * Rotates cell.
	 * @param $direction Direction of rotation. Possible values: <br>
	 * 'right' => right; <br>
	 * 'left' => left. <br>
	 * @access public
	 */
	function rotate($direction = 'right') {	  	
		switch ($direction) {		  
			case 'right':		
				$this->direction = '\cltxtbrl ';	  	
			break;
			
			case 'left':	
				$this->direction = '\cltxbtlr ';	  		
			break;
		} 				
	}		

	/**
	 * Sets background color.
	 * @param string $backColor Background color
	 * @access public
	 */
	function setBackGround($backColor) {
		$backColor = Util::formatColor($backColor);				
		$this->rtf->addColor($backColor);	
		$this->backColor = $backColor;	
	}	

	/**
     * Sets borders of element.    
     * @param BorderFormat $borderFormat
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
		
		if ($top && $this->table->CheckIfCellExists($this->row - 1, $this->column)) {		  
			$cell = &$this->table->getCell($this->row - 1, $this->column); 
			
			if (empty($cell->bordered)) {		  
			  	$cell->bordered = new Bordered();
			}

			$cell->bordered->setBorders($borderFormat, 0, 0, 0, 1);  				
		}		
		
		if ($bottom && $this->table->CheckIfCellExists($this->row + 1, $this->column)) {		  
			$cell = &$this->table->getCell($this->row + 1, $this->column); 
			
			if (empty($cell->bordered)) {		  
			  	$cell->bordered = new Bordered();
			}

			$cell->bordered->setBorders($borderFormat, 0, 1, 0, 0);  				
		}
		
		if ($left && $this->table->checkIfCellExists($this->row, $this->column - 1)) {		  
			$cell = &$this->table->getCell($this->row, $this->column - 1); 

			if (empty($cell->bordered)) {		  
			  	$cell->bordered = new Bordered();
			}

			$cell->bordered->setBorders($borderFormat, 0, 0, 1, 0);  				
		}
		
		if ($right && $this->table->checkIfCellExists($this->row, $this->column + 1)) {		  
			$cell = &$this->table->getCell($this->row, $this->column + 1); 

			if (empty($cell->bordered)) {		  
			  	$cell->bordered = new Bordered();
			}

			$cell->bordered->setBorders($borderFormat, 1, 0, 0, 0);  				
		}
	}
	
	/** 
	 * Gets rtf code of Cell object. Internal use.
	 * @return string
	 * @access public
	 */
	function getContent() {	  	
	  	$content = '{';
		$content .= !empty($this->alignment) ? $this->alignment : '';			  	
		$content .= !empty($this->font) ? $this->font->getContent($this->rtf) : '';	  		
		$content .= Container::getContent().'\cell \pard }'."\r\n";
		return $content;
	}  	
}

?>
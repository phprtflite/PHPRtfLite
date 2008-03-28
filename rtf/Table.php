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
 * Class for creating tables.
 * @package Rtf
 */
class Table {

	/**#@+ 
	 * Internal use.
	 * @access public
	 */   
  	var $rtf;
  	
  	var $container;
  
  	var $rows;
  
  	var $columns;  
  	
  	var $cells;
  	
  	var $alignment;
  	
  	var $keep;
  	
  	var $firstRowHeader;
  	
	var $leftPosition = 0;
	/**#@-*/ 		
	
	/** 
	 * Constructor. Internal use.
	 * @access public
	 */
	function Table(&$container, $alignment = 'left') {	    
		$this->rtf = &$container->rtf;	
		$this->container = &$container;	  
		
		switch ($alignment) {		  
			case 'left':			
				$this->alignment = '\trql ';	
			break;
			
			case 'center':			
				$this->alignment = '\trqc ';
			break;
			
			case 'right':			
				$this->alignment = '\trqr ';
			break;
			
			default:			
				$this->alignment = '\trql ';
			break;
		}		  
	} 
	  	
  	/**
  	 * Sets that the table row is not splited by a page break. By default page break splits table row.
  	 * @access public
  	 */
  	function setRowsKeepTogether() {	    
	    $this->keep = 1;
	}
	
	/**
	 * Sets the first row of table as header. This row is repeated at the top of each page.
  	 * @access public	 
	 */
	function setFirstRowAsHeader() {	 
	 	$this->firstRowHeader = 1;
	}
	
	/**
	 * Sets left position of table.
	 * @param float $leftPosition Left position of table.
     * @access public
	 */
	function setLeftPosition($leftPosition) {
		$this->leftPosition = $leftPosition;
  	}
  	  	  	
  	/**
  	 * Adds row to a table.
  	 * @param float height Height of table row. When 0, the height is sufficient for all the text in the line; when positive, the height is guaranteed to be at least the specified height; when negative, the absolute value of the height is used, regardless of the height of the text in the line.  	 
  	 * @access public
  	 * @todo doc
  	 */
  	function addRow($height = 0) {	    
		if (empty($height)) {		  		  
		    $this->rows[] = 0;
		} else {	    	
			$this->rows[] = $height;    
		}
		
		for ($i = 1; $i <= count($this->columns); $i ++ ) {		  
		  	$this->cells[count($this->rows)][$i] = new Cell($this, count($this->rows), $i);
		}
	}
	
	/**
	 * Adds list of rows to a table.
	 * @param array Array of heights of rows. When height is 0, the height is sufficient for all the text in the line; when positive, the height is guaranteed to be at least the specified height; when negative, the absolute value of the height is used, regardless of the height of the text in the line.
	 * @access public
	 */
	function addRowsList($array) {		
		foreach ($array as $value) {			
			$this->addRow($value);
		}
	}
	
	/**
	 * Adds rows to a table.
	 * @param int $count Count of rows.
	 * @param float $height Height of row. When 0, the height is sufficient for all the text in the line; when positive, the height is guaranteed to be at least the specified height; when negative, the absolute value of the height is used, regardless of the height of the text in the line.
	 * @access public
	 */
	function addRows($count, $height = 0) {		
		for ($i = 1; $i <= $count; $i ++) {			
			$this->addRow($height);
		}
	}
	
	/**
  	 * Adds column to a table.
  	 * @param float $width Width of column. 
  	 * @access public
  	 */
	function addColumn($width) {	  
		$this->columns[] = $width;
		
		for ($i = 1; $i <= count($this->rows); $i ++ ) {		  
		  	$this->cells[$i][count($this->columns)] = new Cell($this, $i, count($this->columns));
		}
	}
	
	/**
	 * Adds list of columns to a table.
	 * @param array Array of column widths.
	 * @access public
	 */
	function addColumnsList($array) {		
		foreach ($array as $value) {			
			$this->addColumn($value);
		}
	}
	
	/**
	 * Gets the instance of cell.
	 * @param int $row 
	 * @param int $column
	 * @access public
	 * @return Cell 
	 */
	function &getCell($row, $column) {	 	
		return $this->cells[$row][$column];		
	}
	
	/**
	 * Writes text to cell.
	 * @param $row Vertical position of cell
	 * @param $column Horizontal position of cell
  	 * @param string $text Text. Also you can use html style tags. @see Container::writeText()
  	 * @param Font $font Font of text
  	 * @param mix $parFormat Paragraph format or null object.
  	 * @param boolen $replaceTags If false, then html style tags are not replaced with rtf code.
  	 * @access public	
	 */
	function writeToCell($row, $column, $text, &$font, &$parFormat, $replaceTags = true) {	  
		if ($this->checkIfCellExists($row, $column)) {		
		  	$cell = &$this->getCell($row, $column);		  	
			$cell->writeText($text, $font, $parFormat, $replaceTags);			
		}
	}
	
	/**
	 * Adds image to cell.
	 * @param $row Vertical position of cell
	 * @param $column Horizontal position of cell
	 * @param string $fileName Name of image file.
   	 * @param mix $parFormat
	 * @param float $width Default 0. If 0 image is displayed by it's height.
	 * @param float $height Default 0. If 0 image is displayed by it' width. If boths parameters are 0, image is displayed as is.	 
	 * @return Image
	 * @access public
	 */	
	function &addImageToCell($row, $column, $fileName, &$parFormat, $width = 0, $height = 0) {
		if ($this->checkIfCellExists($row, $column)) {		
		  	$cell = &$this->getCell($row, $column);		  	
			$cell->addImage($fileName, $parFormat, $width, $height);			
		}
	}	
	
	/** @access private */
	function rowsCols(&$startRow, &$startColumn, &$endRow, &$endColumn) {
		$endRow = empty($endRow) ? $startRow : $endRow; 
		$endColumn = empty($endColumn) ? $startColumn : $endColumn; 		
		
		if ($startRow > $endRow) {		  
		  	$temp = $startRow;
		  	$startRow = $endRow;
		  	$endRow = $temp;
		}
		
		if ($startColumn > $endColumn) {		  
		  	$temp = $startColumn;
		  	$startColumn = $endColumn;
		  	$endColumn = $temp;
		}	  
	}
	
	/**
	 * Sets vertical alignment of cells.
	 * @param $verticalAlignment Vertical alignment of cell (default top). Possible values:<br>
	 * 'top' => top alignment;<br>
	 * 'center' => center alignment;<br>
	 * 'bottom' => bottom alignment.
	 * @param $startRow Start row
	 * @param $startColumn  Start column
	 * @param $endRow End row . If 0, then vertical alignment is set just for one row cells.
	 * @param $endColumn End column . If 0, then vertical alignment is set just for one column cells.	 	 
	 * @access public
	 */	
	function setVerticalAlignmentOfCells($verticalAlignment, $startRow, $startColumn, $endRow = 0, $endColumn = 0) {		
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
			
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);	
					$cell->setVerticalAlignment($verticalAlignment);
				}
			}
		}
	}
	
	/**
	 * Sets alignments of empty cells.	 
	 * @param $alignment Alignment of cell (default top).  The method Cell::writeToCell overrides it with ParFormat alignment.
	 * Possible values:<br>
	 * 'left' => left alignment;<br>
	 * 'center' => center alignment;<br>
	 * 'right' => right alignment;<br>
	 * 'justify' => justify alignment.
	 * @param $startRow Start row
	 * @param $startColumn Start column
	 * @param $endRow End row. If 0, then default alignment is set just for one row cells.
	 * @param $endColumn End column. If 0, then default alignment is set just for one column cells.	 
	 * @access public
	 */	
	function setDefaultAlignmentOfCells($alignment, $startRow, $startColumn, $endRow = 0, $endColumn = 0) {		
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
			
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);	
					$cell->setDefaultAlignment($alignment);
				}
			}
		}
	}
	
	/**
	 * Sets default font of empty cells.
	 * @param $font Default font of empty cell. The method Cell::writeToCell overrides it with another Font.
	 * @param $startRow Start row.
	 * @param $startColumn Start column.
	 * @param $endRow End row. If 0, default font is set just for one row cells.
	 * @param $endColumn End column. If 0, default font is set just for one column cells.	 	
	 * @access public
	 */	
	function setDefaultFontOfCells(&$font, $startRow, $startColumn, $endRow = 0, $endColumn = 0) {		
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
			
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);	
					$cell->setDefaultFont($font);
				}
			}
		}
	}
	
	/**
	 * Rotates cells.
	 * @param $startRow Start row.
	 * @param $startColumn Start column.
	 * @param $endRow End row. If 0, then cells of just one row are rotated.
	 * @param $endColumn End column. If 0, then cells of just on column are rotated.
	 * @param $direction Direction of rotation. Possible values:
	 * 'right' => right;
	 * 'left' => left.
	 * @access public
	 */	
	function rotateCells($direction, $startRow, $startColumn, $endRow = 0, $endColumn = 0) {	
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
			
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);	
					$cell->rotate($direction);				  
				}
			}
		}		
	}
	
	/**
	 * Sets background color of cells.
	 * @param string $backColor Colour of background
	 * @param $startRow Start row
	 * @param $startColumn Star column
	 * @param $endRow End row. If 0, then background color is set just for one row cells.
	 * @param $endColumn End column. If 0, then background color is set just for one column cells.
	 * @access public
	 */
	function setBackGroundOfCells($backColor, $startRow, $startColumn, $endRow = 0, $endColumn = 0) {	
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
			
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);	
					$cell->setBackGround($backColor);	  
				}
			}
		} 
	}	
	
	/**
	 * Sets borders of cells.
	 * @param BorderFormat &$borderFormat Border format
	 * @param $startRow Start row
	 * @param $startColumn Start column
	 * @param $endRow End row. If 0, then border format is set just for one row cells.
	 * @param $endColumn End column. If 0, then border format is set just for one column cells.
	 * @param boolean $left If false, left border is not set (default true)
     * @param boolean $top If false, top border is not set (default true)
     * @param boolean $right If false, right border is not set (default true)
     * @param boolean $bottom If false, bottom border is not set (default true)
	 * @access public
	 */
	function setBordersOfCells(&$borderFormat, $startRow, $startColumn, $endRow = 0, $endColumn = 0, $left = true, $top = true, $right = true, $bottom = true) {	    
		Table::rowsCols($startRow, $startColumn, $endRow, $endColumn);
					
		if ($this->checkIfCellExists($startRow, $startColumn) 
			&& $this->checkIfCellExists($endRow, $endColumn)) {		
			for ($row = $startRow; $row <= $endRow; $row++) {			  
				for ($column = $startColumn; $column <= $endColumn; $column++) {			  
					$cell = &$this->getCell($row, $column);						
					$cell->setBorders($borderFormat, $left, $top, $right, $bottom);	  
				}
			}
		} 	  
	}
	
	/**
	 * Merges cells of table.
	 * @param int $startRow Start row
	 * @param int $startColumn Start column
	 * @param int $endRow End row
	 * @param int $endColumn End column
	 * @access public	 
	 * @since Version 0.3.0
	 */
	function mergeCells($startRow, $startColumn, $endRow, $endColumn) {	  
	  	if ($startRow > $endRow) {		    
		    $temp = $startRow;
		    $startRow = $endRow;
		    $endRow = $temp;		    
		}
		
		if ($startColumn > $endColumn) {		    
		    $temp = $startColumn;
		    $startColumn = $endColumn;
		    $endColumn = $temp;		    
		}
		
		if (!$this->checkIfCellExists($endRow, $endColumn)) {		    
		    return;
		}
					
		for ($j = $startRow; $j <= $endRow; $j ++) {				
			$start = $startColumn;
					
			$cell = &$this->getCell($j, $start);									
			while (!empty($cell->horMerged)) {			  
				$start --;		  	
				$cell = &$this->getCell($j, $start);									
			}
			
			$end = $endColumn;
			
			$cell = &$this->getCell($j, $end + 1);
			while (!empty($cell->horMerged)) {			  
				$end ++;	
				$cell = &$this->getCell($j, $end + 1);	  					
			}
					
			$width = 0;
	
			for ($i = $start; $i <= $end; $i ++) {				
				$cell = &$this->getCell($j, $i);
				
				if ($j == $startRow) {				  
				  	$cell->verStart = true;
				} else {				  
				  	$cell->verMerged = true;
				}
						  
			  	$width += $this->columns[$i - 1];		  	
	
			  	if ($i != $start) {									
					$cell->horMerged = true;
					unset($cell->width);				
				}				
			}			
			
			$cell = &$this->getCell($j, $start);
			$cell->width = $width;
		}				
	}
	
	
	/** @access private */
	function getRowsCount() {	  
	  	return count($this->rows);
	}
	
	/** @access private */
	function getColumnsCount() {	  
	  	return count($this->columns);
	}
		
	/** @access private */
	function checkIfCellExists($row, $column) {	  	  	
	  	if ($row < 1 || $row > count($this->rows) || $column < 1 || $column > count($this->columns)) {				
		    return false;
		}		
		
		return true;
	}
	
	
		
	/** 
	 * Gets rtf code of Table object. Internal use.
	 * @return string
	 * @access public
	 */
	function getContent() {  	  
	  	if (empty($this->rows) || empty($this->columns)) {		    
		    return '';
		}		
	  	
	  	$content = '';			  	  		  		    		
	  	$content .= '\pard ';			  	  			
		$row = 1;
							
		foreach ($this->rows as $value) {		  
		  	$content .= '\trowd '."\r\n";	
		  	
		  	if (!empty($this->alignment)) {		  
			  	$content .= $this->alignment."\r\n";
			}
			
			if (!empty($value)) {			  
				$content .= '\trrh'.round($value * TWIPS_IN_CM);		  	
			}
			
			if (!empty($this->keep)) {			  
			  	$content .= '\trkeep ';
			}
			
			if (!empty($this->firstRowHeader)) {			  
			 	$content .= '\trhdr '; 	
			}						
			
			if (!empty($this->leftPosition)) {			  
			  	$content .= '\trleft'.round($this->leftPosition * TWIPS_IN_CM).' ';
			}
			
			$content .= "\r\n";				
			$width = 0;
			$column = 1;		
				
		  	foreach ($this->columns as $value2) {			    
				$cell = &$this->getCell($row, $column);

				if (empty($cell->horMerged)) {	
					if (empty($cell->width)) {				      	
						$width += round($value2 * TWIPS_IN_CM);
					} else {					  
					  	$width += round($cell->width * TWIPS_IN_CM);					  	
					}					

					if (!empty($cell->verMerged)) {					 
						$content .= '\clvmrg'."\r\n";	 
					} else if (!empty($cell->verStart)) {					  
					 	$content .= '\clvmgf'."\r\n";	  	
					}
					
				    if (!empty($cell->backColor)) {				    	
				    	$backColor = $cell->backColor;
						$content .= '\clcbpat'.$this->rtf->GetColor($backColor).' '."\r\n";
					}
					
					if (!empty($cell->verticalAlignment)) {				    				    	
						$content .= $cell->verticalAlignment."\r\n";
					}
					
					if (!empty($cell->direction)) {				    				    	
						$content .= $cell->direction."\r\n";
					}	
					
					if (!empty($cell->bordered)) {					
						$content .= $cell->bordered->getContent($this->rtf, '\\cl');								
					}		
					
					$content .= '\cellx'.$width.' '."\r\n";
			
				}
				$column ++;			    
			}
			
			//@since version 2.0
			$content .= '\pard \intbl '."\r\n";
			
			$column = 1;
			
			foreach ($this->columns as $value2) {			  
			  	$cell = &$this->GetCell($row, $column);
			  	if (empty($cell->horMerged)) {
					$content .= $cell->getContent();
				}				
				$column ++;
			}			
			
			$content .= '\pard \intbl \row '."\r\n";
			$row ++;			
		} 	
		
		$content .= '\pard'."\r\n";		
		return $content;
	}
}
?>
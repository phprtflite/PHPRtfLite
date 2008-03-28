<?php
require_once("../rtf/Rtf.php");

//////////////
//Font formats
$font1 = new Font(11, 'Times new Roman', '#000055');

//////////////
//Paragraph formats
$parFC = new ParFormat('center');

$parFL = new ParFormat('left');

//////////////
//Rtf document
$rtf = new Rtf();
$null = null;
//section
$sect = &$rtf->addSection();

$sect->writeText('Chess tournamet information (write your data)
', new Font(14, 'Arial'), new ParFormat());

$chessPlayers = array('Mike Smith', 'Jim Morgan', 'Jochgan Berg', 'Bill Scott', 'Bill Martines', 'John Po', 'Aleck Harrison', 'Ann Scott', 'Johnatan Fredericson', 'Eva Carter');

/*$chessResults = (array(
				array(0  , 1  , 0.5, 1  , 0  , 1  , 0  , 0.5  , 1  , 1  ),
				array(0, 0, 0.5, 0.5, 0, 0, 0, 0, 0, 1),
				array(0.5, 1, 0, 1, 0.5, 1, 0.5, 0, 0, 1),
				array(0, 0.5, 0.5, 0, 0, 0.5, 0.5, 0, 0, 1),				


				array(1, 0.5, 1, 0, 0, 0.5, 0.5, 0, 0, 1),			

));*/

$count = count($chessPlayers);
$countCols = $count + 2;
$countRows = $count + 1;

$colWidth = ($sect->getLayoutWidth() - 5) / $count;

//table creating and rows ands columns adding
$table = &$sect->addTable();
$table->addRows(1, 2);
$table->addRows($count, -0.6);

$table->addColumn(3);
for ($i = 1; $i <= count($chessPlayers); $i ++) {	
	$table->addColumn($colWidth);
}
$table->addColumn(2);

//borders
$table->setBordersOfCells(new BorderFormat(1, '#555555'), 1, 1, $countRows, $countCols);

//top row
$table->rotateCells('right', 1, 2, 1, $countCols - 1);
$table->setVerticalAlignmentOfCells('center', 1, 2, 1, $countCols);

$i = 2;
foreach ($chessPlayers as $player) {  
  	$table->writeToCell(1, $i, $player, $font1, $null);  	
  	$table->writeToCell($i, 1, $player, $font1, new ParFormat(), $null);  	
  	$table->setBordersOfCells(new BorderFormat(1, '#0000ff'), $i, $i);
  	$table->setBackgroundOfCells('#dddddd', $i, $i);  		
  	$i ++;
}

//tournament result
/*$i = 1;
foreach ($chessResults as $playerResult) {  
  	$j = 1; 
  	$sum = 0;
  	foreach ($playerResult as $result)  {
  	  	if ($i != $j) {
			$table->writeToCell($i + 1, $j + 1, $result, new Font(11, 'Times new Roman', '#7A2900'), new ParFormat('center'));  		
			$sum += $result;	    
		}
		$j ++;		
	}
	$table->writeToCell($i + 1, $j + 1, '<b>'.$sum.'</b>', new Font(11, 'Times new Roman', '#7A2900'), new ParFormat('center'));  
	$i ++;
}*/

$fontBold = new Font(11, 'Times new Roman', '#7A2900');
$fontBold->setBold();

$table->setDefaultAlignmentOfCells('center', 2, 2, $countRows, $countCols);
$table->setDefaultFontOfCells(new Font(11, 'Times new Roman', '#7A2900'), 2, 2, $countRows, $countCols - 1);
$table->setDefaultFontOfCells($fontBold, 2, $countCols, $countRows);

$table->writeToCell(1, $countCols, 'TOTAL', $font1, new ParFormat('center'));

$table->setBordersOfCells(new BorderFormat(1.5, '#000000'), 1 , $countCols, $countRows, $countCols);
$table->setBordersOfCells(new BorderFormat(1, '#0000ff', 'dash'), 2, $countCols, $countRows - 1, $countCols, 0, 0, 0, 1);

$sect->writeText('Chess tournamet play-offs (write your data)
', new Font(14, 'Arial'), new ParFormat());


$count = 8;
$rows = 16;


$countSmall = 5;
$countLarge = 6;

$smallWidth = '0.75';
$bigWidth = ($sect->getLayoutWidth() - $countSmall * $smallWidth) / $countLarge;

$table = &$sect->addTable();
$table->addRows(16, -0.5);
$table->addColumnsList(array($smallWidth, $bigWidth, $bigWidth, $smallWidth, $smallWidth, $bigWidth, $bigWidth, $smallWidth, $smallWidth, $bigWidth, $bigWidth));

$table->setDefaultAlignmentOfCells('center', 1, 1, 16, 11);
$table->setDefaultFontOfCells(new Font(11, 'Times new Roman', '#7A2900'), 1, 1, 16, 11);


$table->setBordersOfCells(new BorderFormat(1), 2, 1, 3, 3);
$table->setBordersOfCells(new BorderFormat(1), 6, 1, 7, 3);
$table->setBordersOfCells(new BorderFormat(1), 10, 1, 11, 3);
$table->setBordersOfCells(new BorderFormat(1), 14, 1, 15, 3);

$table->setBordersOfCells(new BorderFormat(1), 4, 5, 5, 7);
$table->setBordersOfCells(new BorderFormat(1), 12, 5, 13, 7);

$table->setBordersOfCells(new BorderFormat(1), 8, 9, 9, 11);
$table->setBordersOfCells(new BorderFormat(1), 14, 9, 15, 11);

$table->setBordersOfCells(new BorderFormat(1), 1, 10, 3, 11);

$table->writeToCell(2, 1, 'P1', $font1, $null);
$table->writeToCell(3, 1, 'P8', $font1, $null);
$table->writeToCell(6, 1, 'P2', $font1, $null);
$table->writeToCell(7, 1, 'P7', $font1, $null);
$table->writeToCell(10, 1, 'P3', $font1, $null);
$table->writeToCell(11, 1, 'P6', $font1, $null);
$table->writeToCell(14, 1, 'P4', $font1, $null);
$table->writeToCell(15, 1, 'P5', $font1, $null);

$table->writeToCell(1, 1, 'A1', $font1, $null);
$table->writeToCell(5, 1, 'A2', $font1, $null);
$table->writeToCell(9, 1, 'A3', $font1, $null);
$table->writeToCell(13, 1, 'A4', $font1, $null);

$table->writeToCell(3, 5, 'B1', $font1, $null);
$table->writeToCell(4, 5, 'A1', $font1, $null);
$table->writeToCell(5, 5, 'A2', $font1, $null);

$table->writeToCell(11, 5, 'B2', $font1, $null);
$table->writeToCell(12, 5, 'A3', $font1, $null);
$table->writeToCell(13, 5, 'A4', $font1, $null);

$table->writeToCell(7, 10, '1-st place', $font1, $null);
$table->writeToCell(8, 9, 'B1', $font1, $null);
$table->writeToCell(9, 9, 'B2', $font1, $null);

$table->writeToCell(13, 10, '3-d place', $font1, $null);
$table->writeToCell(14, 9, 'B1', $font1, $null);
$table->writeToCell(15, 9, 'B2', $font1, $null);


$table->setBackgroundOfCells('#ffff88', 1, 10, 1, 11);
$table->setBackgroundOfCells('#cccccc', 2, 10, 2, 11);
$table->setBackgroundOfCells('#ffAA66', 3, 10, 3, 11);


$table->writeToCell(1, 10, '1-st place', $font1, $null);
$table->writeToCell(2, 10, '2-st place', $font1, $null);
$table->writeToCell(3, 10, '3-d place', $font1, $null);

$rtf->sendRtf();

?>
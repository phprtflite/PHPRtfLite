<?php
require_once("../rtf/Rtf.php");

$rtf = new Rtf();
$sect = &$rtf->addSection();

$table = &$sect->addTable();

$table->addRows(5, 0.75);
$table->addColumnsList(array(3, 3, 3, 3, 3));

$table->mergeCells(1, 1, 3, 1);
$table->writeToCell(1, 1, 'Vertical merged cells.', new Font(), new ParFormat());
$table->setBordersOfCells(new BorderFormat(1, "#ff0000"), 1, 1, 3, 1);

$table->mergeCells(1, 3, 1, 5);
$table->writeToCell(1, 3, 'Horizontal merged cells', new Font(), new ParFormat());
$table->setBordersOfCells(new BorderFormat(1, "#0000ff"), 1, 3, 1, 5);

$table->mergeCells(3, 3, 5, 5);
$table->writeToCell(3, 3, 'Horizontal and vertical merged cells', new Font(), new ParFormat());
$table->setBordersOfCells(new BorderFormat(1, "#00ff00"), 3, 3, 5, 5);

$rtf->save("bla.rtf");
$rtf->sendRtf();
?>
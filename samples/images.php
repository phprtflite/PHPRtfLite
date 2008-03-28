<?php

require_once("../rtf/Rtf.php");

//paragraph formats
$parF = new ParFormat();

$parGreyLeft = new ParFormat();
$parGreyLeft->setShading(10);

$parGreyCenter = new ParFormat('center');
$parGreyCenter->setShading(10);

$rtf = new Rtf();
$null = null;

$header = &$rtf->addHeader('first');
$header->addImage('../sources/rtf_thumb.jpg', $parF);
$header->writeText(' Image in header.', new Font(), new ParFormat());

$sect = &$rtf->addSection();
$sect->writeText('Images with PhpRtf.', new Font(14), new ParFormat('center'));

$sect->writeText('<br>Here is .jpg image. <tab>', new Font(), new ParFormat());
$sect->addImage('../sources/rtf_thumb.jpg', $null);

$sect->writeText('<br>Here is .png image. <tab>', new Font(), new ParFormat());
$sect->addImage('../sources/html.png', $null);

$sect->writeText('<br><br><b>Formating sizes of images:</b>', new Font(), new ParFormat());

$table = &$sect->addTable();
$table->addRows(3, 4.5);
$table->addRow(6);
$table->addColumnsList(array(7.5, 6.5));

$table->writeToCell(1, 1, '<br> Original size.', new Font(), new ParFormat());
//getting cell object, writing text and adding image
$cell = &$table->getCell(1, 2);
$cell->writeText('<br>   ', new Font(), new ParFormat());
$cell->addImage('../sources/cats.jpg', $null);

$table->writeToCell(2, 1, '<br> Width is set.', new Font(), new ParFormat());
//writing to cell and adding image from table object
$table->writeToCell(2, 2, '<br>   ', new Font(), new ParFormat());
$table->addImageToCell(2, 2, '../sources/cats.jpg', $null, 5);

$table->writeToCell(3, 1, '<br> Height is set.', new Font(), new ParFormat());
$table->writeToCell(3, 2, '<br>   ', new Font(), new ParFormat());
$table->addImageToCell(3, 2, '../sources/cats.jpg', $null, 0, 3.5);

$table->writeToCell(4, 1, '<br> Both: width and height are set.', new Font(), new ParFormat());
$cell = &$table->getCell(4, 2);
$cell->writeText('<br>   ', new Font(), new ParFormat());
$img = $cell->addImage('../sources/cats.jpg', $null, 3, 5);

$sect->writeText('<page/><b>Borders of images</b>', new Font(), new ParFormat());
$table = &$sect->addTable();
$table->addRows(2, 4.5);
$table->addColumnsList(array(7.5, 6.5));

$table->writeToCell(1, 1, '<br> Sample borders', new Font(), new ParFormat());
$cell = &$table->getCell(1, 2);
$cell->writeText('<br>   ', new Font(), new ParFormat());
$img = &$cell->addImage('../sources/cats.jpg', $null);
$img->setBorders(new BorderFormat(3, '#000000'));

$table->writeToCell(2, 1, '<br> Borders with space', new Font(), new ParFormat());
$cell = &$table->getCell(2, 2);
$cell->writeText('<br>   ', new Font(), new ParFormat());
$img = &$cell->addImage('../sources/cats.jpg', $null);
$img->setBorders(new BorderFormat(2, '#0000ff', 'simple', 0.5));
$img->setBorders(new BorderFormat(2, '#ff0000', 'simple', 0.5), true, false, true, false);

$sect->writeRtfCode('\par ');

$sect->writeText('<b>Images in paragraph</b><br><br>', new Font(), $parGreyLeft);
$img = &$sect->addImage('../sources/html.png', $parGreyCenter);
$img->setWidth(1.5);

$rtf->sendRtf('Images');

?>
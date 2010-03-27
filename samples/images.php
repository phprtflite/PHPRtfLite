<?php

require '../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

//paragraph formats
$parFormat = new PHPRtfLite_ParFormat();

$parGreyLeft = new PHPRtfLite_ParFormat();
$parGreyLeft->setShading(10);

$parGreyCenter = new PHPRtfLite_ParFormat('center');
$parGreyCenter->setShading(10);

// rtf document
$rtf = new PHPRtfLite();

// header
$header = $rtf->addHeader('first');
$header->addImage('sources/rtf_thumb.jpg', $parFormat);
$header->writeText('Image in header.', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());

$sect = $rtf->addSection();
$sect->writeText('Images with PhpRtf.', new PHPRtfLite_Font(14), new PHPRtfLite_ParFormat('center'));

$sect->writeText('<br>Here is .jpg image. <tab>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$sect->addImage('sources/rtf_thumb.jpg', null);

$sect->writeText('<br>Here is .png image. <tab>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$sect->addImage('sources/html.png', null);

$sect->writeText('<br><br><b>Formating sizes of images:</b>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());

$table = $sect->addTable();
$table->addRows(3, 4.5);
$table->addRow(6);
$table->addColumnsList(array(7.5, 6.5));

$table->writeToCell(1, 1, '<br> Original size.', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
//getting cell object, writing text and adding image
$cell = $table->getCell(1, 2);
$cell->writeText('<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$cell->addImage('sources/cats.jpg', null);

$table->writeToCell(2, 1, '<br> Width is set.', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
//writing to cell and adding image from table object
$table->writeToCell(2, 2, '<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$table->addImageToCell(2, 2, 'sources/cats.jpg', null, 5);

$table->writeToCell(3, 1, '<br> Height is set.', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$table->writeToCell(3, 2, '<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$table->addImageToCell(3, 2, 'sources/cats.jpg', null, 0, 3.5);

$table->writeToCell(4, 1, '<br> Both: width and height are set.', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$cell = $table->getCell(4, 2);
$cell->writeText('<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$img = $cell->addImage('sources/cats.jpg', null, 3, 5);

$sect->writeText('<page/><b>Borders of images</b>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$table = $sect->addTable();
$table->addRows(2, 4.5);
$table->addColumnsList(array(7.5, 6.5));

$table->writeToCell(1, 1, '<br> Sample borders', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$cell = $table->getCell(1, 2);
$cell->writeText('<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$img = $cell->addImage('sources/cats.jpg', null);
$border = PHPRtfLite_Border::create(3, '#000000');
$img->setBorder($border);

$table->writeToCell(2, 1, '<br> Borders with space', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$cell = $table->getCell(2, 2);
$cell->writeText('<br>   ', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
$img = $cell->addImage('sources/cats.jpg', null);

$borderFormatBlue = new PHPRtfLite_Border_Format(2, '#0000ff', 'simple', 0.5);
$borderFormatRed = new PHPRtfLite_Border_Format(2, '#ff0000', 'simple', 0.5);
$border = new PHPRtfLite_Border();
$border->setBorderLeft($borderFormatRed);
$border->setBorderTop($borderFormatBlue);
$border->setBorderRight($borderFormatRed);
$border->setBorderBottom($borderFormatBlue);
$img->setBorder($border);

$sect->writeRtfCode('\par ');

$sect->writeText('<b>Images in paragraph</b><br><br>', new PHPRtfLite_Font(), $parGreyLeft);
$img = $sect->addImage('sources/html.png', $parGreyCenter);
$img->setWidth(1.5);

// send to browser
$rtf->sendRtf('images.rtf');
<?php

require '../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

//Fonts
$fontHead = new PHPRtfLite_Font(12, 'Arial');
$fontSmall = new PHPRtfLite_Font(3);
$fontAnimated = new PHPRtfLite_Font(10);
$fontLink = new PHPRtfLite_Font(10, 'Helvetica', '#0000cc');

$parBlack = new PHPRtfLite_ParFormat();
$parBlack->setIndentRight(9);
$parBlack->setBackgroundColor('#000000');
$parBlack->setSpaceBefore(12);

$parHead = new PHPRtfLite_ParFormat();
$parHead->setSpaceBefore(3);
$parHead->setSpaceAfter(8);

$parSimple = new PHPRtfLite_ParFormat();
$parSimple->setIndentLeft(5);
$parSimple->setIndentRight(0.5);

$parPhp = new PHPRtfLite_ParFormat();
$parPhp->setShading(5);
$border = PHPRtfLite_Border::create($rtf, 1, '#000000', 'dash', 0.3);
$parPhp->setBorder($border);
$parPhp->setIndentLeft(5);
$parPhp->setIndentRight(0.5);

//Rtf document
$rtf = new PHPRtfLite();
//section
$sect = $rtf->addSection();

//table
$table = $sect->addTable();
$table->addRows(1);
$table->addRows(1);
$table->addColumn(1);
$table->addColumn(14);

$cell = $table->getCell(1, 2);
$cell->writeText('Testing paragraphs in table cells.', new PHPRtfLite_Font(14, 'Arial'), $parHead);

$cell = $table->getCell(2, 2);

$cell->emptyParagraph($fontSmall, $parBlack);
$cell->writeText('Various paragraphs', $fontHead, $parHead);

$par = new PHPRtfLite_ParFormat('center');
$par->setIndentLeft(10);
$par->setBackgroundColor('#99ccff');
$par->setSpaceBetweenLines(2);

$cell->writeText('Alignment: center
Indent Left: 10
BackColor: #99ccff', new PHPRtfLite_Font(8, 'Verdana'), $par);

$par = new PHPRtfLite_ParFormat('right');
$par->setIndentLeft(5);
$par->setIndentRight(5);
$par->setBackgroundColor('#ffcc99');
$border = PHPRtfLite_Border::create($rtf, 1, '#ff0000');
$par->setBorder($border);

$cell->writeText('', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());

$cell->writeText('Alignment: right
Indent Left: 5
Indent Right: 10
BackColor: #ffcc99
Border: red', new PHPRtfLite_Font(8, 'Verdana'), $par);

//send to browser
$rtf->sendRtf('paragraphs_in_tables.rtf');
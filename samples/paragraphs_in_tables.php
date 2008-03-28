<?php
require_once("../rtf/Rtf.php");

//Fonts
$fontHead = new Font(12, 'Arial');
$fontSmall = new Font(3);
$fontAnimated = new Font(10);
$fontLink = new Font(10, 'Helvetica', '#0000cc');

$parBlack = new ParFormat();
$parBlack->setIndentRight(9);
$parBlack->setBackColor('#000000');
$parBlack->setSpaceBefore(12);

$parHead = new ParFormat();
$parHead->setSpaceBefore(3);
$parHead->setSpaceAfter(8);

$parSimple = new ParFormat();
$parSimple->setIndentLeft(5);
$parSimple->setIndentRight(0.5);

$parPhp = new ParFormat();
$parPhp->setShading(5);
$parPhp->setBorders(new BorderFormat(1, '#000000', 'dash', 0.3));
$parPhp->setIndentLeft(5);
$parPhp->setIndentRight(0.5);

//////////////
//Rtf document
$rtf = new Rtf();
//section
$sect = &$rtf->addSection();


$table = &$sect->addTable();
$table->addRows(1);
$table->addRows(1);
$table->addColumn(1);
$table->addColumn(14);

$cell = &$table->getCell(1, 2);
$cell->writeText('Testing paragraphs in table cells.', new Font(14, 'Arial'), $parHead);

$cell = &$table->getCell(2, 2);

$cell->emptyParagraph($fontSmall, $parBlack);
$cell->writeText('Various paragraphs', $fontHead, $parHead);

$par = new ParFormat('center');
$par->setIndentLeft(10);
$par->setBackColor('#99ccff');
$par->setSpaceBetweenLines(2);

$cell->writeText('Alignment: center
Indent Left: 10
BackColor: #99ccff', new Font(8, 'Verdana'), $par);

$par = new ParFormat('right');
$par->setIndentLeft(5);
$par->setIndentRight(5);
$par->setBackColor('#ffcc99');
$par->setBorders(new BorderFormat(1, '#ff0000'));

$cell->writeText('', new Font, new ParFormat());

$cell->writeText('Alignment: right
Indent Left: 5
Indent Right: 10
BackColor: #ffcc99
Border: red', new Font(8, 'Verdana'), $par);

$rtf->prepare();
$rtf->sendRtf();


?>
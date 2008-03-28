<?php

require_once("../rtf/Rtf.php");

//Fonts
$fontHead = new Font(12, 'Arial');
$fontSmall = new Font(3);
$fontAnimated = new Font(10);
$fontLink = new Font(10, 'Helvetica', '#0000cc');

$parBlack = new ParFormat();
$parBlack->setIndentRight(12.5);
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


//Rtf document
$rtf = new Rtf();
$rtf->setMargins(3, 1, 1 ,2);

//Section
$sect = &$rtf->addSection();
$sect->writeText('Paragraphs, fonts and other', new Font(14, 'Arial'), $parHead);

$sect->emptyParagraph($fontSmall, $parBlack);
$sect->writeText('Various fonts', $fontHead, $parHead);

$sect->writeText('Times new Roman, 9pt, Red', new Font(9, 'Times New Roman', '#ff0000'), $parSimple);
$sect->writeText('Times new Roman, 10pt, Red, Pattern Yellow', new Font(10, 'Times New Roman', '#ff0000', '#ffff00'), $parSimple);
$sect->writeText('Tahoma, 10pt, Blue', new Font(10, 'Tahoma', '#0000ff'), $parSimple);
$sect->writeText('Verdana, 8pt, Green', new Font(8, 'Verdana', '#00cc00'), $parSimple);

$sect->emptyParagraph($fontSmall, $parBlack);
$sect->writeText('Various paragraphs', $fontHead, $parHead);

$par = new ParFormat('center');
$par->setIndentLeft(10);
$par->setBackColor('#99ccff');
$par->setSpaceBetweenLines(2);

$sect->writeText('Alignment: center
Indent Left: 10
BackColor: #99ccff', new Font(8, 'Verdana'), $par);

$par = new ParFormat('right');
$par->setIndentLeft(5);
$par->setIndentRight(5);
$par->setBackColor('#ffcc99');
$par->setBorders(new BorderFormat(1, '#ff0000'));

$sect->writeText('', new Font, new ParFormat());

$sect->writeText('Alignment: right
Indent Left: 5
Indent Right: 10
BackColor: #ffcc99
Border: red', new Font(8, 'Verdana'), $par);

$sect->emptyParagraph($fontSmall, $parBlack);
$sect->writeText('Using hyperlinks', $fontHead, $parHead);
$sect->writeHyperlink('http://www.php.lt', 'Official phpRtf site.', $fontLink, $parSimple);

$sect->emptyParagraph($fontSmall, $parBlack);
$sect->writeText('Using tags', $fontHead, $parHead);

$sect->writeText('<b>Bold text.</b><i>Italic<u>Underline text.</u></i><tab>.Current date- <chdate>. Bullet <bullet><br>', new Font(), $parSimple);
$sect->writeText('<b>Bold text.</b><i>Italic<u>Underline text.</u></i><tab>.Current date- <chdate>. Bullet <bullet>.<br>', new Font(), $parSimple, false);

$sect->emptyParagraph($fontSmall, $parBlack);
$sect->writeText('PHP highlighting sample', $fontHead, $parHead);

$sect->writeText('//sample php code<br/ >', new Font(11, 'Courier New', '#ff8800'), $parPhp);

$null = null;
$sect->writeText('$sum = $a + $b;<br/ >', new Font(11, 'Courier New', '#0000AA'), $null);
$sect->writeText('echo ', new Font(11, 'Courier New', '#008800'), $null);
$sect->writeText('"The sum is - "', new Font(11, 'Courier New', '#AA0000'), $null);
$sect->writeText('.$sum.', new Font(11, 'Courier New', '#0000AA'), $null);
$sect->writeText('" ."', new Font(11, 'Courier New', '#AA0000'), $null);
$sect->writeText(';', new Font(11, 'Courier New', '#000000'), $null);


$rtf->sendRtf();
?>
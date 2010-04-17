<?php

$dir = dirname(__FILE__);
require_once $dir . '/../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

//rtf document
$rtf = new PHPRtfLite();

$borderFormat = new PHPRtfLite_Border_Format(10, '#833', PHPRtfLite_Border_Format::TYPE_DOTDASH, 2);
$rtf->setBorders($borderFormat);

$header = $rtf->addHeader();
$header->writeText('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at velit imperdiet neque volutpat lobortis.');

$section = $rtf->addSection();
$section->writeText('Section I Text with page break');
$section->insertPageBreak();
$section->writeText('Text after page break');

$section = $rtf->addSection();
$section->writeText('Section II Text with special header');
$header = $section->addHeader();
$header->writeText('Section header');

$section = $rtf->addSection();
$section->writeText('Section III Text without special header');
$borderFormat = new PHPRtfLite_Border_Format(20, '#338', PHPRtfLite_Border_Format::TYPE_DASH, 10);
$border = new PHPRtfLite_Border($rtf);
$border->setBorders($borderFormat);
$section->setBorder($border);

// save rtf document
$rtf->save($dir . '/generated/border_header.rtf');
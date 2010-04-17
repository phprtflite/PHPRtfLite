<?php

$dir = dirname(__FILE__);
require_once $dir . '/../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

$rtf = new PHPRtfLite();
$sect = $rtf->addSection();
$sect->writeText('<i>Hello <b>World</b></i>.', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat('center'));

// save rtf document
$rtf->save($dir . '/generated/hello_world.rtf');
<?php

require '../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

$rtf = new PHPRtfLite();
$sect = $rtf->addSection();
$sect->writeText('<i>Hello <b>World</b></i>.', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat('center'));

$rtf->sendRtf('HelloWorld');
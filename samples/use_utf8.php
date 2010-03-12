<?php

require '../lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

//Rtf document
$rtf = new PHPRtfLite();

//Font
$times12 = new PHPRtfLite_Font(12, 'Times new Roman');

//Section
$sect = $rtf->addSection();
//Write utf-8 encoded text.
//Text is from file. But you can use another resouce: db, sockets and other
$sect->writeText(file_get_contents('sources/utf8.txt'), $times12, null);

//sends rft document
$rtf->sendRtf('utf8.rtf');
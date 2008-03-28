<?php

require_once("../rtf/Rtf.php");

//Fonts
$times12 = new Font(12, 'Times new Roman');

//Rtf document
$rtf = new Rtf();

//Section
$sect = &$rtf->addSection();
$null = $null;
//Write utf-8 encoded text.
//Text is from file. But you can use another resouce: db, sockets and other
$sect->writeText(file_get_contents("../sources/utf8.txt"), $times12, $null);

$rtf->sendRtf();

?>
<?php

require_once("../rtf/Rtf.php");

$rtf = new Rtf();
$sect = &$rtf->addSection();
$sect->writeText('<i>Hello <b>World</b></i>.', new Font(12), new ParFormat('center'));

$rtf->sendRtf('Hello World');

?>
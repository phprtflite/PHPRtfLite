<?php
error_reporting(E_ALL);
require_once("../rtf/Rtf.php");
require_once("../rtf/RtfTemplate.php");

$template = new RtfTemplate('../sources/template_2003.rtf', '../sources/');


$template->sendRtf('Hello World');

?>
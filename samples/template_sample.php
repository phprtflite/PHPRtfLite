<?php
error_reporting(E_ALL);
require_once("../rtf/Rtf.php");
require_once("../rtf/RtfTemplate.php");

$template = new RtfTemplate('../sources/template_2000.rtf', '../sources');

$markText = $template->createMark('mark_for_text');
$null = &$null;
$markText->writeText("Just simple text", new Font(14, 'Arial', '#ff0000'), new ParFormat('center'));
$markText->writeText('Another row', new Font(10, 'Times New Roman', '#0000ff'), new ParFormat('right'));

$markTable = $template->createMark('mark_for_table');
$table = $markTable->addTable('center');

$table->addRows(3);
$table->addColumnsList(array(3, 3, 3));


$template->sendRtf('Hello World');

?>
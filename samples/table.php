<?php

$dir = dirname(__FILE__);
require_once $dir . '/../lib/PHPRtfLite.php';

$rowCount = 3;
$rowHeight = 1;
$columnCount = 4;
$columnWidth = 3;

PHPRtfLite::registerAutoloader();
$rtf = new PHPRtfLite();
$sect = $rtf->addSection();

$table = $sect->addTable();
$table->addRows($rowCount, $rowHeight);
$table->addColumnsList(array_fill(0, $columnCount, $columnWidth));

for ($rowIndex = 1; $rowIndex <= $rowCount; $rowIndex++) {
    for ($columnIndex = 1; $columnIndex <= $columnCount; $columnIndex++) {
        $cell = $table->getCell($rowIndex, $columnIndex);
        $cell->writeText("Cell $rowIndex:$columnIndex");
        $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
        $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);
    }
}

// save rtf document
$rtf->save($dir . '/generated/table.rtf');
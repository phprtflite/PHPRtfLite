<?php

/**
 * Nested
 *
 * Created on   29.11.2010
 *
 * @author      sz
 */
class PHPRtfLite_Table_Nested extends PHPRtfLite_Table
{

    public function render()
    {
        if (empty($this->_rows) || empty($this->_columns)) {
            return;
        }

        $stream = $this->getRtf()->getStream();

        foreach ($this->_rows as $row) {
            $this->renderRowCells($row);
        }
    }

}
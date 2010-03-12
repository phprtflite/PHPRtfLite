<?php
/*
    PHPRtfLite
    Copyright 2010 Steffen Zeidler <sigma_z@web.de>

    This file is part of PHPRtfLite.

    PHPRtfLite is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PHPRtfLite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with PHPRtfLite.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Class for creating columns of table in rtf documents.
 * @version     1.0.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2009 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_Table
 */
class PHPRtfLite_Table_Column
{

    /**
     * column width
     * @var float
     */
    protected $_width;


    /**
     * Constructor
     *
     * @param float $width
     */
    public function __construct($width) {
        $this->_width = $width;
    }

    /**
     * Sets column width
     *
     * @param float $width
     */
    public function setWidth($width) {
        $this->_width = $width;
    }

    /**
     * Gets column width
     * 
     * @return float
     */
    public function getWidth() {
        return $this->_width;
    }

}
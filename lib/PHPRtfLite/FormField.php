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
 * abstract class for form fields in rtf documents.
 * @version     1.1.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_FormField
 */
class PHPRtfLite_FormField
{
    const TYPT_TEXT     = 0;
    const TYPE_CHECKBOX = 1;
    const TYPE_LIST     = 2;

    protected $_font;

    protected $_parFormat;

    protected $_type;

    protected $_defaultValue;


    abstract public function render();
    

    /**
     * constructor
     *
     * @param   PHPRtfLite              $rtf
     * @param   integer                 $type
     * @param   PHPRtfLite_Font         $font
     * @param   PHPRtfLite_ParFormat    $parFormat
     */
    public function __construct(PHPRtfLite $rtf, $type = null,
                                PHPRtfLite_Font $font = null, PHPRtfLite_ParFormat $parFormat = null)
    {
        $this->_rtf         = $rtf;
        $this->_type        = $type;
        $this->_font        = $font;
        $this->_parFormat   = $parFormat;
    }


    public function setDefaultValue($value)
    {
        $this->_defaultValue = $value;
    }

}

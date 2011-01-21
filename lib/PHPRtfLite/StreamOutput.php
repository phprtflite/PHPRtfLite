<?php
/*
    PHPRtfLite
    Copyright 2007-2008 Denis Slaveckij <info@phprtf.com>
    Copyright 2010-2011 Steffen Zeidler <sigma_z@web.de>

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
 * Class for streaming the rtf output.
 * @version     1.1.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010-2011 Steffen Zeidler
 * @package     PHPRtfLite
 */
class PHPRtfLite_StreamOutput
{

    /**
     * file handler for stream
     *
     * @var resource
     */
    private $_fileHandler;

    
    /**
     * opens file stream
     * 
     * @param   string  $filename
     */
    public function open($filename)
    {
        $this->_fileHandler = fopen($filename, 'wb');
        if (!$this->_fileHandler) {
            throw new PHPRtfLite_Exception("Could not open file '$filename' for stream!");
        }
        flock($this->_fileHandler, LOCK_EX);
    }


    /**
     * closes file handler
     */
    public function close()
    {
        if ($this->_fileHandler !== null) {
            fclose($this->_fileHandler);
            $this->_fileHandler = null;
        }
    }


    /**
     * writes string to file handler
     *
     * @param string $string
     */
    public function write($string)
    {
        if ($this->_fileHandler === null) {
            $this->open();
        }

        fwrite($this->_fileHandler, $string);
    }

}
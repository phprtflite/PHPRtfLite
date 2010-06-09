<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../../../lib/PHPRtfLite.php';
require_once dirname(__FILE__) . '/../../Mocks/StreamOutputMock.php';

/**
 * Test class for PHPRtfLite_Container_Header.
 */
class PHPRtfLite_Container_HeaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPRtfLite
     */
    protected $_rtf;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // register PHPRtfLite class loader
        PHPRtfLite::registerAutoloader();

        $streamMock = new PHPRtfLite_StreamOutputMock;
        $this->_rtf = new PHPRtfLite($streamMock);
    }

    /**
     * tests render
     */
    public function testRender()
    {
        $header = new PHPRtfLite_Container_Header($this->_rtf);
        $header->writeText('hello world and see my rtf header!');
        $header->render();
        $this->assertEquals('{\header {hello world and see my rtf header!}'
                          . "\r\n\par}\r\n",
                            $this->_rtf->getStream()->content);
    }

 
}
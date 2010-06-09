<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../../../lib/PHPRtfLite.php';
require_once dirname(__FILE__) . '/../../Mocks/StreamOutputMock.php';

/**
 * Test class for PHPRtfLite_Element_Hyperlink
 */
class PHPRtfLite_Element_HyperlinkTest extends PHPUnit_Framework_TestCase
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
        $streamMock = new PHPRtfLite_StreamOutputMock;
        $this->_rtf = new PHPRtfLite($streamMock);
    }

    /**
     * tests render().
     */
    public function testRender()
    {
        $hyperlink = new PHPRtfLite_Element_Hyperlink($this->_rtf, 'My link text!');
        $hyperlink->setHyperlink('http://www.phprtf.com/');
        $hyperlink->render();
        $expected = '{\field {\*\fldinst {HYPERLINK "http://www.phprtf.com/"}}{\fldrslt {My link text!}}}';
        $this->assertEquals($expected, trim($this->_rtf->getStream()->content));
    }

}
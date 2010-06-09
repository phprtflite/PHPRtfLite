<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../../lib/PHPRtfLite.php';
require_once dirname(__FILE__) . '/../Mocks/StreamOutputMock.php';

/**
 * Test class for PHPRtfLite_Footnote.
 */
class PHPRtfLite_FootnoteTest extends PHPUnit_Framework_TestCase
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
        $footnote = new PHPRtfLite_Footnote($this->_rtf, 'hello rtf world!');
        $footnote->render();
        $this->assertEquals('\chftn {\footnote\pard\plain \lin283\fi-283 {\up6\chftn}'
                          . "\r\n" . 'hello rtf world!} ',
                            $this->_rtf->getStream()->content);
    }
}
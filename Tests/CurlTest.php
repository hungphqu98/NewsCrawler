<?php 

use PHPUnit\Framework\TestCase;

/**
* CurlTest
* 
*/
class CurlTest extends TestCase
{
    protected $curl;

    /**
     * Create an instance of curl to test
     */
    protected function setUp(): void
    {
        $this->curl = new \Services\Curl();
    }
    
    /**
     * Test if Curl handler null at first
     */
    public function testGetHandler()
    {
        $this->assertEquals(null, $this->curl->handler);
    }

    /**
     * Test if Curl handler not null after init
     */
    public function testHandlerNotNullAfterInit() 
    {
        $this->curl->init();

        $this->assertNotEquals(null, $this->curl->handler);
    }

    /**
     * Test if curl url is empty at first
     */
    public function testUrlEmpty()
    {
        $this->assertEquals("", $this->curl->url);
    }

    /**
     * Test if url inputted is the same as url of curl request
     */
    public function testUrlAfterSetOpt()
    {
        $this->curl->init();

        $this->curl->getUrl('example.com');
        $this->curl->getMethod('GET');
        $this->curl->setOptArray();

        $this->assertEquals('example.com', $this->curl->getInfo()["url"]);
    }

    /**
     * Test if request method is GET
     */
    public function testRequestMethodIsGet()
    {
        $this->assertEquals("GET", $this->curl->method);
    }

    /**
     * Test if curl exec function not return null
     */
    public function testCurlExecNotReturnNull()
    {
        $this->curl->init();

        $this->curl->getUrl('example.com');
        $this->curl->getMethod('GET');
        $this->curl->setOptArray();

        $this->assertNotNull($this->curl->exec());
    }
}
 


?>
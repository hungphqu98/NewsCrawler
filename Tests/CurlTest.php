<?php 
use PHPUnit\Framework\TestCase;

/**
* CurlTest
* 
*/
class CurlTest extends TestCase
{
    protected $curl;

    protected function setUp(): void
    {
        $this->curl = new Services\Curl();
    }
    
    public function testGetHandler()
    {
        // Test if Curl handler null at first
        $this->assertEquals(null, $this->curl->handler);
    }

    public function testHandlerNotNullAfterInit() 
    {
        // Test if Curl handler not null after init
        $this->curl->init();

        $this->assertNotEquals(null, $this->curl->handler);
    }

    public function testUrlEmpty()
    {
        // Test if curl url is empty at first
        $this->assertEquals("", $this->curl->url);
    }

    public function testUrlAfterSetOpt()
    {
        // Test if url inputted is the same as url of curl request
        $this->curl->init();

        $this->curl->getUrl('example.com');
        $this->curl->getMethod('GET');
        $this->curl->setOptArray();

        $this->assertEquals('example.com', $this->curl->getInfo()["url"]);
    }

    public function testRequestMethodIsGet()
    {
        // Test if request method is GET
        $this->assertEquals("GET", $this->curl->method);
    }

    public function testCurlExecNotReturnNull()
    {
        // Test if curl exec function not return null
        $this->curl->init();

        $this->curl->getUrl('example.com');
        $this->curl->getMethod('GET');
        $this->curl->setOptArray();

        $this->assertNotNull($this->curl->exec());
    }
    
}
 


?>
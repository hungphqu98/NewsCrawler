<?php

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {
    
    protected $anonymousParser;

    /**
     * Set up an anonymous class that extends abstract class Parser to test concrete class
     */
    protected function setUp(): void
    {
        $this->anonymousParser = new class(new Services\Curl) extends Parser\Parser {
            public $curl;
            public function __construct(\Services\Curl $curl)
            {
                $this->curl = $curl;
            }
        };

    }
    
    /**
     * Test if Curl request is executed correctly
     */
    public function testCurlExecution() 
    {
        $mockCurl = $this->createMock(Services\Curl::class);

        $mockCurl->init();

        $mockCurl->getUrl('example.com');
        $mockCurl->getMethod('GET');
        $mockCurl->setOptArray();
        $mockCurl->method('exec')->willReturn('Result');

        $this->assertEquals('Result', $mockCurl->exec());
    }
  
    public function testParseReturnArray()
    {
        $mockParser = $this->getMockBuilder(Parser\Parser::class)->setConstructorArgs([new Services\Curl])->setMethods(['getParse'])->getMockForAbstractClass(Parser\Parser::class);

        $result = ['title' => 'random title','content' => 'random content','date' => 'random date'];
        $mockParser->expects($this->once())->method('getParse')->willReturn($result);

        $this->assertEquals($result,$mockParser->getParse());

    }

    /**
     * Data provider for title testing
     */
    public function titleProvider()
    {
        return [
            array('Sample title no error', 'Sample title no error'),
            array(' With spaces ', 'With spaces'),
            array(' With        whitespace       ', 'With whitespace'),
            array(" Have 'quotes' ", "Have \'quotes\'")
          ];
    }

    /**
     * @dataProvider titleProvider
     * Test if title is formatted 
     */
    public function testTitleFormatted($titleRaw,$titleExpected)
    {
        $this->assertEquals($titleExpected,$this->anonymousParser->formatTitle($titleRaw));
    }

    /**
     * Test if content is formatted, escapes single quotes
     */
    public function testContentFormatted()
    {
        $contentRaw = "Sample content 'with' quotes";
        $contentExpected = "Sample content \'with\' quotes";
        $this->assertEquals($contentExpected,$this->anonymousParser->formatContent($contentRaw));
    }

    /**
     * Data provider for date testing
     */
    public function dateProvider()
    {
        return [
            array('24/12/2019 14:30', '24/12/2019 14:30'),
            array('24/12/2019 Thứ năm 14:30', '24/12/2019 14:30'),
            array('3/2/2019 5:30PM ', '3/2/2019 5:30'),
            array('03/02/2019 5:30 GMT+7 ', '03/02/2019 5:30')
          ];
    }

    /**
     * @dataProvider dateProvider
     * Test if date is formatted
     */
    public function testdateFormatted($dateRaw,$dateExpected)
    {
        $this->assertEquals($dateExpected,$this->anonymousParser->formatDate($dateRaw));
    }


}

?>
<?php

use PHPUnit\Framework\TestCase;

/**
 * ParserTest
 */
class ParserTest extends TestCase {
    
    protected $anonymousParser;
    
    protected $mockParser;

    /**
     * Set up an anonymous class that extends abstract class Parser to test concrete class
     */
    protected function setUp(): void
    {   
        $this->mockParser = $this->getMockBuilder(Parser\Parser::class)->setConstructorArgs([new Services\Curl])->setMethods(['getParse','htmlParse','getTitle','getContent','getDate'])->getMockForAbstractClass(Parser\Parser::class);

        $this->anonymousParser = new class(new Services\Curl) extends Parser\Parser {
            public $curl;
            public function __construct(\Services\Curl $curl)
            {
                $this->curl = $curl;
            }
        };

    }

    /**
     * Test if Curl request is not empty
     */
    public function testCurlEmpty()
    {
        $mockCurl = $this->createMock(Services\Curl::class);
    
        $this->assertNotEmpty($mockCurl);
    }
    
    /**
     * Test if Curl request is executed correctly
     */
    public function testCurlExecution() 
    {
        $mockCurl = $this->createMock(Services\Curl::class);
        $curlResult = " class='title tag' , class='content tag' , class = 'date tag' ";
        $mockCurl->init();

        $mockCurl->getUrl('example.com');
        $mockCurl->getMethod('GET');
        $mockCurl->setOptArray();
        $mockCurl->method('exec')->willReturn($curlResult);
        
        $this->assertEquals($curlResult, $mockCurl->exec());

        return $mockCurl;
    }

    /**
     * @depends testCurlExecution
     * Test if html get contains given query tag of title,content,date
     */
    public function testHtmlContainSiteTag($mockCurl) {
        
        $this->assertStringContainsString('title tag',$mockCurl->exec());
        $this->assertStringContainsString('content tag',$mockCurl->exec());
        $this->assertStringContainsString('date tag',$mockCurl->exec());

    }

    /**
     * Test if curl result can be transformed to a domXPath object
     */
    public function testHtmlParseReturnDomXPathObject()
    {
        $this->mockParser->expects($this->any())->method('htmlParse')->willReturn('DomXPathObject');

        $this->assertEquals('DomXPathObject',$this->mockParser->htmlParse());
    }

    /**
     * Test if title get is not empty
     */
    public function testTitleGetNotEmpty()
    {
        $this->mockParser->expects($this->any())->method('getTitle')->with('news')->willReturn('Title');

        $this->assertNotEmpty($this->mockParser->getTitle('news'));
    }

    /**
     * Test if title get is a string
     */
    public function testTitleGetIsAString()
    {
        $this->mockParser->expects($this->any())->method('getTitle')->with('news')->willReturn('A title string');

        $this->assertIsString($this->mockParser->getTitle('news'));
    }

    /**
     * Test if title get is not long (less than 100 characters)
     */
    public function testTitleGetIsNotTooLong()
    {

        $title = "A title shorter than 100 characters";
        $this->mockParser->expects($this->any())->method('getTitle')->with('news')->willReturn($title);

        $this->assertLessThan(100,strlen($title));
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
     * Test if content get is not empty
     */
    public function testContentGetNotEmpty()
    {
        $this->mockParser->expects($this->any())->method('getContent')->with('news')->willReturn('Content');

        $this->assertNotEmpty($this->mockParser->getContent('news'));
    }

    /**
     * Test if content get is a string
     */
    public function testContentGetIsAString()
    {
        $this->mockParser->expects($this->any())->method('getContent')->with('news')->willReturn('A content string');

        $this->assertIsString($this->mockParser->getContent('news'));
    }

    /**
     * Test if content get is not too short (more than 100 characters)
     */
    public function testContentGetIsNotTooShort()
    {

        $content = "Chiều 24/8, Phó thủ tướng Phạm Bình Minh chủ trì cuộc họp Tổ công tác của Chính phủ về ngoại giao vắc xin. Cuộc họp nhằm rà soát, đánh giá tiến độ và kết quả triển khai các nhiệm vụ ngoại giao vắc xin thời gian qua; thống nhất nhiệm vụ cần quyết liệt thực hiện nhằm tiếp cận, đưa vắc xin, trang thiết bị y tế và thuốc điều trị về nước nhanh nhất, nhiều nhất và sớm nhất có thể.";
        $this->mockParser->expects($this->any())->method('getTitle')->with('news')->willReturn($content);

        $this->assertGreaterThan(100,strlen($content));
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
     * Test if date get is not empty
     */
    public function testDateGetNotEmpty()
    {
        $this->mockParser->expects($this->any())->method('getDate')->with('news')->willReturn('Date');

        $this->assertNotEmpty($this->mockParser->getDate('news'));
    }

    /**
     * Test if date get is a string
     */
    public function testDateGetIsAString()
    {
        $this->mockParser->expects($this->any())->method('getDate')->with('news')->willReturn('A date string');

        $this->assertIsString($this->mockParser->getDate('news'));
    }

    /**
     * Test if date get has date type
     */
    public function testDateGetHasActualDate()
    {
        $date = '24/2/2021';

        $this->mockParser->expects($this->any())->method('getDate')->with('news')->willReturn($date);

        $this->assertMatchesRegularExpression('^\\d{1,2}/\\d{1,2}/\\d{4}^',$this->mockParser->getDate('news'));
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

    /**
     * Test if getParse function actually return an array
     */
    public function testParseReturnArray()
    {

        $result = ['title' => 'random title','content' => 'random content','date' => 'random date'];
        $this->mockParser->expects($this->once())->method('getParse')->willReturn($result);

        $this->assertIsArray($this->mockParser->getParse());

    }

}

?>
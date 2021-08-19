<?php

use Classes\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {
  
  public function testCurlExecution() 
  {
      $mockCurl = $this->createMock(Services\Curl::class);

      $mockCurl->init();

      $mockCurl->getUrl('example.com');
      $mockCurl->getMethod('GET');
      $mockCurl->get();
      $mockCurl->method('exec')->willReturn('Result');

      $this->assertEquals('Result', $mockCurl->exec());
  }
  
  public function testParseReturnArray()
  {
      $curl = new Services\Curl;
      $mockParser = $this->getMockBuilder(\Parser\Parser::class)->setConstructorArgs([$curl])->setMethods(['getParse'])->getMockForAbstractClass(\Parser\Parser::class);

      $result = ['title' => 'random title','content' => 'random content','date' => 'random date'];
      $mockParser->expects($this->once())->method('getParse')->willReturn($result);

      $this->assertEquals($result,$mockParser->getParse());

  }

  public function testTitleFormatted()
  {
      $curl = new Services\Curl;
      $mockParser = $this->getMockBuilder(\Parser\Parser::class)->setConstructorArgs([$curl])->setMethods(['getTitle'])->getMockForAbstractClass(\Parser\Parser::class);

      $titleGot = '';
      $titleFormatted = '';

      $mockParser->expects($this->any())->method('getTitle')->with('sample')->willReturn($titleFormatted);
  }



}

?>
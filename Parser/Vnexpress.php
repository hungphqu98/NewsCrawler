<?php 
namespace Parser;

use Parser\Parser;

/**
   * This is parser class for VnExpress pages containing html tag information for query
   */
  class Vnexpress extends Parser {
    
    protected $titleQuery = "//*[@class='title-detail']";
    protected $contentQuery = "//*[@class='Normal']";
    protected $dateQuery = "//*[@class='date']";
 
 }

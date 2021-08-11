<?php 
namespace Parser;

use Parser\Parser;

/**
   * This is parser class for Vietnamnet pages containing html tag information for query
   */
  class Vietnamnet extends Parser {
    
    protected $titleQuery = "//*[@class='title f-22 c-3e']";
    protected $contentQuery = "//*[@class='ArticleContent']/p";
    protected $dateQuery = "//*[@class='ArticleDate']";
  
  }


?>

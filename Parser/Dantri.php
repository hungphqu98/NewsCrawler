<?php 
namespace Parser;

use Parser\Parser;

/**
   * This is parser class for Dantri pages containing html tag information for query
   */
  class Dantri extends Parser {

    protected $titleQuery = "//*[@class='dt-news__title']";
    protected $contentQuery = "//*[@class='dt-news__content']/p";
    protected $dateQuery = "//*[@class='dt-news__time']";

  }

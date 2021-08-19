<?php 
namespace Parser;

class ParserChild extends Parser {

  public function getTitle($news) {
    return parent::getTitle($news);
  }

  public function getContent($news) {
    return parent::getContent($news);
  }

  public function getDate($news) {
    return parent::getDate($news);
  }

}

?>
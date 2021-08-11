<?php 
namespace Parser;

/**
 * This is the abstract Parser class. Other subparser class extend this class. 
 * This class is used to parse data
 */
abstract class Parser {

  /**
   * @var Curl
   */
  protected $curl;

  /**
   * @var string title query
   */
  protected $titleQuery = "";

  /**
   * @var string content query
   */
  protected $contentQuery = "";

  /**
   * @var string date query
   */
  protected $dateQuery = "";

  /**
   * @var string parsed title
   */
  protected $title;

  /**
   * @var string parsed content
   */
  protected $content;

  /**
   * @var string parsed date
   */
  protected $date;

  /**
   * Use an instance of Curl when construct the page parser
   */
  public function __construct(\Services\Curl $curl) {

    $this->curl = $curl;
    if(!$curl){
      throw new \Exception('Can not construct page parser');
    }

  }

  /**
   * Get parsed data of title,content,date and store result in an array
   * 
   * @return array
   */
  public function getParse() {

    // Parse page data from html
    $news = $this->htmlParse();

    // Get title,content,data data
    $title = $this->getTitle($news);
    $content = $this->getContent($news);
    $date = $this->getDate($news);

    // Create a result array from the data and return it
    $result = array(
      'title' => $title,
      'content' => $content,
      'date' => $date
    );
    return $result;

  }

  /**
   * Create a DOMDocument to retrieve XML & HTML data to parse with PHP and return a DomXPath object
   * 
   * @return DomXPath object
   */
  public function htmlParse() {

    // Execute curl request
    $html = $this->curl->exec();

    // Create a DomDocument 
    $dom = new \DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html,LIBXML_NOERROR);

    // Return a DOMXPath object
    $parse = new \DOMXPath($dom);
    return $parse;
    
  }

  /**
   * Get and format title from query data
   * 
   * @return string
   */
  protected function getTitle($news) {

    $dataTitle = $news->query($this->titleQuery);
    $titles = $dataTitle->item(0)->nodeValue;

    // Remove whitespace if present
    $titles = preg_replace('/\s+/', ' ', $titles);

    // Escape single quotes
    $titles = str_replace("'", "\'", $titles);

    $title = trim($titles," ");

    return $this->title = $title;

  }

  /**
   * Get and format content from query data
   * 
   * @return string
   */
  protected function getContent($news) {
    $dataContent = $news->query($this->contentQuery);

    // Join data from different lines
    $content = '';
    foreach ($dataContent as $line) {
      $content .= $line->nodeValue;
    }

    // Escape single quotes
    $content = str_replace("'", "\'", $content);

    return $this->content = $content;
  }

  /**
   * Get and format content from query data
   * 
   * @return string 
   */
  protected function getDate($news) {
    $dataDate = $news->query($this->dateQuery);
    $dateString = $dataDate->item(0)->nodeValue;

    // Get only date & time data from string
    preg_match('^\\d{1,2}/\\d{1,2}/\\d{4}^',$dateString,$day);
    preg_match('^\\d{1,2}:\\d{1,2}^',$dateString,$time);
    $date = $day[0]. " " .$time[0];

    return $this->date = $date;
  }


}

?>

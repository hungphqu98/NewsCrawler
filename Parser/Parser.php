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
    echo "<pre>";
    var_dump($news);
    echo "</pre>";
    // Get title,content,data data
    $titleRaw = $this->getTitle($news);
    $contentRaw = $this->getContent($news);
    $dateRaw = $this->getDate($news);

    // Parse
    $title = $this->formatTitle($titleRaw);
    $content = $this->formatContent($contentRaw);
    $date = $this->formatDate($dateRaw);

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
    echo "<pre>";
    var_dump($html);
    echo "</pre>";
    // Create a DomDocument 
    $dom = new \DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html,LIBXML_NOERROR);
    
    // Return a DOMXPath object
    $parse = new \DOMXPath($dom);
    return $parse;
    
  }

  /**
   * Get title from query data
   * 
   * @return string
   */
  public function getTitle($news) {

    $dataTitle = $news->query($this->titleQuery);
    $titleRaw = $dataTitle->item(0)->nodeValue;
    return $titleRaw;

  }

  /**
   * Format title data to insert DB
   * 
   * @return string
   */
  public function formatTitle($titleRaw) {
    // Remove whitespace if present
    $titleRaw = preg_replace('/\s+/', ' ', $titleRaw);

    // Escape single quotes
    $titleRaw = str_replace("'", "\'", $titleRaw);

    $title = trim($titleRaw," ");

    return $this->title = $title;
  }

  /**
   * Get content from query data
   * 
   * @return string
   */
  public function getContent($news) {
    $dataContent = $news->query($this->contentQuery);

    // Join data from different lines
    $contentRaw = '';
    foreach ($dataContent as $line) {
      $contentRaw .= $line->nodeValue;
    }

    return $contentRaw;
  }

  /**
   * Format content data to insert DB
   * 
   * @return string
   */
  public function formatContent($contentRaw) {
    // Escape single quotes
    $content = str_replace("'", "\'", $contentRaw);

    return $this->content = $content;
  }

  /**
   * Get date from query data
   * 
   * @return string 
   */
  public function getDate($news) {
    $dataDate = $news->query($this->dateQuery);
    $dateRaw = $dataDate->item(0)->nodeValue;

    return $dateRaw;
  }

  /**
   * Format date data to insert DB
   * 
   * @return string
   */
  public function formatDate($dateRaw) {
    // Get only date & time data from string
    preg_match('^\\d{1,2}/\\d{1,2}/\\d{4}^',$dateRaw,$day);
    preg_match('^\\d{1,2}:\\d{1,2}^',$dateRaw,$time);
    $date = $day[0]. " " .$time[0];

    return $this->date = $date;
  }


}

?>

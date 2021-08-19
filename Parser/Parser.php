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
   * @var string received title
   */
  protected $titleReceived;

  /**
   * @var string received content
   */
  protected $contentReceived;

  /**
   * @var string received date
   */
  protected $dateReceived;

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
  public function getTitle($news) {

    $dataTitle = $news->query($this->titleQuery);
    $titleReceived = $dataTitle->item(0)->nodeValue;
    return $titleReceived;

  }

  /**
   * 
   */
  public function formatTitle($titleReceived) {
    // Remove whitespace if present
    $this->titleReceived = preg_replace('/\s+/', ' ', $this->titleReceived);

    // Escape single quotes
    $this->titleReceived = str_replace("'", "\'", $this->titleReceived);

    $title = trim($this->titleReceived," ");

    return $this->title = $title;
  }

  /**
   * Get and format content from query data
   * 
   * @return string
   */
  public function getContent($news) {
    $dataContent = $news->query($this->contentQuery);

    // Join data from different lines
    $contentReceived = '';
    foreach ($dataContent as $line) {
      $contentReceived .= $line->nodeValue;
    }

    return $contentReceived;
  }

  /**
   * 
   */
  public function formatContent($contentReceived) {
    // Escape single quotes
    $content = str_replace("'", "\'", $contentReceived);

    return $this->content = $content;
  }

  /**
   * Get and format content from query data
   * 
   * @return string 
   */
  public function getDate($news) {
    $dataDate = $news->query($this->dateQuery);
    $dateReceived = $dataDate->item(0)->nodeValue;

    return $dateReceived;
  }

  /**
   * 
   */
  public function formatDate($dateReceived) {
    // Get only date & time data from string
    preg_match('^\\d{1,2}/\\d{1,2}/\\d{4}^',$dateReceived,$day);
    preg_match('^\\d{1,2}:\\d{1,2}^',$dateReceived,$time);
    $date = $day[0]. " " .$time[0];

    return $this->date = $date;
  }


}

?>

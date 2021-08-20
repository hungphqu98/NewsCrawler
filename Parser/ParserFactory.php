<?php 
namespace Parser;

/**
 * This is the ParserFactory class
 * 
 */
class ParserFactory {

  /**
   * Use an instance of Curl class
   */
  private $curl;

  public function __construct(\Services\Curl $curl) {

    $this->curl = $curl;
    if(!$curl){
      throw new \Exception('Can not construct parser');
    }
    
  }

  /**
   * Get the url from Curl request and select a subparser depending on the URL
   * Return an array of the parsed result
   * @return array
   */
  public static function parse($curl) {

    // Get url from curl 
    $url = $curl->getInfo()["url"];

    // Filter page url using regex
     if (preg_match('/dantri.com/',$url)) {
        $parser = new Dantri($curl);
      } else if (preg_match('/vnexpress.net/',$url)) {
        $parser = new Vnexpress($curl);
      } else if (preg_match('/vietnamnet.vn/',$url)) {
        $parser = new Vietnamnet($curl);
      } else if (empty($url)){
        echo '<pre>';
        echo "No URl sent";
        echo '</pre>';
      } else {
        echo '<pre>';
        echo "Only URLs from dantri,vietnamnet,vnexpress are allowed";
        echo '</pre>';
      }

      // Return result for database query
      $result = $parser->getParse();
      return $result;
  }

}


?>

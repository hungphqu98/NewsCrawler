<?php
namespace Services;

/**
 * This is the Curl wrapper class
 * @package curl
 */

class Curl {
  /**
   * @var null curl handler
   */
  public $handler = null;

  /**
   * @var string curl url
   */
  public $url = '';

  /**
   * Default curl request method is GET in this example
   * @var string
   */
  public $method = 'GET';


  
  /**
   * Initialize a Curl object
   */
  public function init() {

    if ($this->handler == null) {
      $this->handler = curl_init();
    }

  }

  /**
   * Return url of current Curl request
   * 
   * @return string
   */
  public function getUrl($url='') {
    $this->url = $url;
    return $this;

  }
  
  /**
   * Change curl request method. Restricted to GET for this project
   * 
   * @return string
   */
  public function method($method = 'GET') {
    
    $this->method = $method;
    if ($method != 'GET') {
      echo '<pre>';
      echo "Only GET requests with curl are supported";
      echo '</pre>';
    }
    return $this;

  }

  /**
   * Set Curl request option, including url,method
   * 
   * @return array
   */
  public function setOptArray() {
    
    return curl_setopt_array($this->handler,[
      CURLOPT_URL => $this->url,
      CURLOPT_CUSTOMREQUEST => $this->method,
      CURLOPT_RETURNTRANSFER => true,
    ]);

  }

  /**
   * Get data from curl request
   * 
   */
  public function get() {

    $this->init();
    $this->getUrl($_POST['url'])->method('GET')->setOptArray();

  }

  /**
   * Perform a Curl session
   * 
   * @return boolean|string
   */
  public function exec() {

    return curl_exec($this->handler);

  }

  /** 
   * Get curl request information
   * 
   * @return array
   */ 
  public function getInfo() {

    return curl_getinfo($this->handler);

  }

  /**
   * Get curl version
   * 
   * @return array|false
   */
  public function version() {

    return curl_version();

  }

  /**
   * Return curl error code
   * 
   * @return string
   */
  public function strError($errnum) {

    return curl_strerror($errnum);

  } 

  /**
   * Return last error number
   * 
   * @return int
   */
  public function errNo() {

    return curl_errno($this->handler);

  }

  /**
   * Return curl error
   * 
   * @return string
   */
  public function error() {

    return curl_error($this->handler);

  }

  /**
   * Get curl handler
   */
  public function getHandler() {

    return $this->handler;

  }

  /**
   * Reset curl request
   */
  public function reset() {

    return curl_reset($this->handler);
    
  }

  /**
   * Close curl request
   */
  public function close() {

    curl_close($this->handler);
    $this->handler = null;

  }

}

?>

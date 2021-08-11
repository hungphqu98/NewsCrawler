<?php 
namespace DB;

/**
 * This is DB class for connecting to a mysql database
 */
class DB {

  /**
   * Open the database connection using the credentials from config.php  
   */ 
  protected function connect() {
      
      // Connect to the database with the credentials
      $conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      if ($conn -> connect_errno) {
        echo "Failed to connect to MySQL: " . $conn -> connect_error;
        exit();
      }

      // Set charset for Vietnamese words
      $conn -> set_charset("utf8");

      return $conn;
    }

}


?>
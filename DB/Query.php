<?php 
namespace DB;

/**
 * This is the query class for database interaction
 */
class Query extends DB {
  
  /**
   * Insert to DB using an array data
   */
  public function insert($data) {

    // Get keys and values from the array data to insert to DB
    $string = "INSERT INTO `news` (" . implode(",",array_keys($data)) . ") VALUES ('" . implode("','", array_values($data)) . "')";
    
    if (mysqli_query($this->connect(),$string)) {
      echo "<br>";
      echo "<strong>Data added to database!</strong>";
      return true;
    } else {
      echo "<br/>";
      echo "Cannot add to database";
    }
  }

}



?>
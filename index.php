<?php 

  /**
   * Prioritize loading application config before other application classes
   */
  require 'Config/Config.php';
  require 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <form method="post" action="index.php">
    <p>
      <label for="url">URL:</label>
      <input style="width: 50%" type="text" name="url" id="url">
    </p>
    <button type="submit">Submit</button>
  </form>
  <hr>

  <?php 
  
  // Only process if form is submitted
  if ($_POST) {
  
  // Start curl request
  $curl = new Services\Curl();
  $curl->get();
  
  // Parse data based on Url submitted
  $result = Parser\ParserFactory::parse($curl);
  var_dump($result);
  
  // Insert parsed data to db
  $data = new DB\Query();
  $data->insert($result);
  
  // Close curl connection
  $curl->close();

  }
  ?>

</body>

</html>

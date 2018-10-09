<?php
include_once 'Database.php';
include_once 'utilities.php';

  if (empty($_GET)) {
    echo "Redemption key required.";
  } else {
    $code = $_GET['code'];

    $jeff = $db->prepare('SELECT * FROM codes WHERE link = :code');
    $jeff->bindParam(':code', $code, PDO::PARAM_STR);
    $jeff->execute();
    $result = $jeff->fetch(PDO::FETCH_ASSOC);

    $ip = $_SERVER['REMOTE_ADDR'];

    $link = $result['link'];
    $downloads = $result['downloads'];
    $id = $result['id'];

    if($link == $code) {
      if($downloads < 3) {
        try{
        $select= $db->prepare('SELECT downloads FROM codes WHERE link = :code');
        $select->bindParam(':code', $code, PDO::PARAM_STR);
        $select->execute();
        $downloads = $select->fetch(PDO::FETCH_ASSOC);

        $update = $db->prepare('UPDATE codes SET downloads = downloads + 1 WHERE id = :id');
        $update->bindParam(':id', $id, PDO::PARAM_STR);
        $update->execute();
      } catch (PDOException $ex){
             echo $ex->getMessage();
        }

        // Logging
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d H:i:s');
        $event = "Download Initiated";

        try{
            $logQuery = "INSERT INTO events (ip, code, timestamp, event)
          VALUES (:ip, :code, :timestamp, :event)";
            $statement = $db->prepare($logQuery);
            $statement->execute(array(':ip' => $ip, ':code' => $code, ':timestamp' => $timestamp, ':event' => $event));
          } catch (PDOException $ex){
               $result = flashMessage("An error occurred: " .$ex->getMessage());
          }

        $file_name = "file.data";
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        echo "Your download will start momentarily.";
      } else {
        echo "You have used your 3 downloads, please contact support if you feel this is incorrect.";
        // Logging
        date_default_timezone_set('America/New_York');
        $timestamp = date('Y-m-d H:i:s');
        $event = "Download limit reached";

        try{
            $logQuery = "INSERT INTO events (ip, code, timestamp, event)
          VALUES (:ip, :code, :timestamp, :event)";
            $statement = $db->prepare($logQuery);
            $statement->execute(array(':ip' => $ip, ':code' => $code, ':timestamp' => $timestamp, ':event' => $event));
          } catch (PDOException $ex){
               $result = flashMessage("An error occurred: " .$ex->getMessage());
          }
      }
    } else {
      echo "You have entered an incorrect download code.";
      // Logging
      date_default_timezone_set('America/New_York');
      $timestamp = date('Y-m-d H:i:s');
      $event = "Incorrect download code";

      try{
          $logQuery = "INSERT INTO events (ip, code, timestamp, event)
        VALUES (:ip, :code, :timestamp, :event)";
          $statement = $db->prepare($logQuery);
          $statement->execute(array(':ip' => $ip, ':code' => $code, ':timestamp' => $timestamp, ':event' => $event));
        } catch (PDOException $ex){
             $result = flashMessage("An error occurred: " .$ex->getMessage());
        }
    }
  }
?>

<html>
<head>
  <title>Download Page</title>
</head>
<body>
</body>
</html>

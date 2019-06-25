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

          ignore_user_abort(true);
          set_time_limit(0); // disable the time limit for this script
           
          $path = "/var/www/html/download/"; // change the path to fit your websites document structure
           
          $dl_file = 'OOS.mp4';
          $fullPath = $path.$dl_file;
          echo "Your download will start momentarily.";
           
          if ($fd = fopen ($fullPath, "r")) {
              $fsize = filesize($fullPath);
              $path_parts = pathinfo($fullPath);
              header("Content-type: application/octet-stream");
              header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
              header("Content-length: " . $fsize);
              header("Cache-control: private"); //use this to open files directly
              while(!feof($fd)) {
                  $buffer = fread($fd, 2048);
                  echo $buffer;
              }
          }
          fclose ($fd);
          exit;
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

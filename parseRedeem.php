<?php
// yay php
include_once 'Database.php';
include_once 'utilities.php';

if(isset($_POST['redeemBtn'])){
   // processing the form
    $form_errors = "";

    // validation
    $required_fields = array('code', 'ip');

    // check empty fieldset
		$form_errors = check_empty_fields($required_fields);

		// code check
		$fields_to_check_length = array('code' => 12);

    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //collect data to sell to the government
    $code = $_POST['code'];
    $ip = $_SERVER['REMOTE_ADDR'];

    // check if code is used
    $jeff = $db->prepare('SELECT * FROM codes WHERE code = :code');
    $jeff->bindParam(':code', $code, PDO::PARAM_STR);
    $jeff->execute();
    $result = $jeff->fetch(PDO::FETCH_ASSOC);

    $id = $result['id'];

    if(($result['code']) == $code){
        if(($result['is_used']) == 0){
        		// preparing and inputting data
        		try
        		{
              // generate download link (this makes no sense but whatever)
              $fuck = $code;
              $explode = str_split($fuck);
              sort($explode);
              $implode = implode('', $explode);
              $partOne = md5($implode);
              $explodeTwo = str_split($partOne);
              sort($explodeTwo);
              $implodeTwo = implode('', $explodeTwo);
              $key = md5($implodeTwo);

              $sth = $db->prepare('UPDATE codes SET is_used = 1 WHERE code = :code');
              $sth->bindParam(':code', $code, PDO::PARAM_STR);
              $sth->execute();

              $sqlagain = $db->prepare('UPDATE codes SET ip_address = :ip WHERE code = :code');
              $sqlagain->bindParam(':code', $code, PDO::PARAM_STR);
              $sqlagain->bindParam(':ip', $ip, PDO::PARAM_STR);
              $sqlagain->execute();

              $linkshit = $db->prepare('UPDATE codes SET link = :link WHERE code = :code');
              $linkshit->bindParam(':link', $key, PDO::PARAM_STR);
              $linkshit->bindParam(':code', $code, PDO::PARAM_STR);
              $linkshit->execute();

              // Kyle, front-end starts here.
              /* You can do ?> anywhere and write straight HTML, or you can just parse it in PHP. Your call. */
              /* Just make sure you start <?php again where its supposed to. */
              echo "Your download should start automatically."; // It shouldn't, fuck that.
              echo "<br />";
              echo "If not, ";
              // Modify this link (localhost:8080/DigitalDownload/ portion)
              $link = "http://localhost:8888/DigitalDownload/download.php?code=$key";
              ?><a href="<?php echo $link; ?>">here's your unique download link.</a>
              <?php

              // Logging
              date_default_timezone_set('America/New_York');
              $timestamp = date('Y-m-d H:i:s');
              $event = "Code Redeemed";

              try{
                  $logQuery = "INSERT INTO events (ip, code, timestamp, event)
                VALUES (:ip, :code, :timestamp, :event)";
                  $statement = $db->prepare($logQuery);
                  $statement->execute(array(':ip' => $ip, ':code' => $code, ':timestamp' => $timestamp, ':event' => $event));
                } catch (PDOException $ex){
                     $result = flashMessage("An error occurred: " .$ex->getMessage());
                }

              // ...and (frontend) ends here
              // you would do <?php here..
        }
        catch (PDOException $ex)
    		{
    		   $ex->getMessage();
    		}
      } else {
        // and again here!
      echo "This code has already been used";
      // Logging
      date_default_timezone_set('America/New_York');
      $timestamp = date('Y-m-d H:i:s');
      $event = "Redemption code has been used.";

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
  echo "You have entered an invalid redemption code";
  // Logging
  date_default_timezone_set('America/New_York');
  $timestamp = date('Y-m-d H:i:s');
  $event = "Invalid Redemption Code";

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

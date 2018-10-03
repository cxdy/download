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

        if(($result['is_used']) == 0){
        		// preparing and inputting data
        		try
        		{
              // generate download link
              $fuck = $code;
              $explode = str_split($fuck);
              sort($explode);
              $implode = implode('', $explode);
              $key = md5($implode);

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

              echo "Redirecting you to the download..";
        }
        catch (PDOException $ex)
    		{
    		   $ex->getMessage();
    		}
      } else {
      echo "This code has already been used";
    }
} else {
  //do nothing
}

?>

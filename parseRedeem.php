<?php
// yay php
include_once 'Database.php';
include_once 'utilities.php';

if(isset($_POST['redeemBtn'], $_POST['token'])){
  if(validate_token($_POST['token'])){
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
    $ip = $_POST['ip'];

    // check if code is used
    $jeff = $db->prepare('SELECT * FROM codes WHERE code = :code');
    $jeff->bindParam(':code', $code, PDO::PARAM_STR);
    $jeff->execute();
    $result = $jeff->fetch(PDO::FETCH_ASSOC);

    $id = $result['id'];

    // name jeff
    echo $result['is_used'];

        if(($result['is_used']) == 0){
        		// preparing and inputting data
        		try
        		{
              $sth = $db->prepare('UPDATE codes SET is_used = 1 WHERE code = :code');
              $sth->bindParam(':code', $code, PDO::PARAM_STR);
              $sth->execute();

              $sqlagain = $db->prepare('UPDATE codes SET ip_address = :ip WHERE code = :code');
              $sqlagain->bindParam(':code', $code, PDO::PARAM_STR);
              $sqlagain->bindParam(':ip', $ip, PDO::PARAM_STR);
              $sqlagain->execute();

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
  //diagnostics - will remove eventually
  echo $code;
  echo $ip;
  echo "===========================";
  echo $result['id'];
  echo $result['code'];
  echo $result['is_used'];
  echo $result['ip'];
}
}
?>

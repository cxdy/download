<?php
/*
| Download Code Generation
| Usage: php -f redeem.php <amount-of-codes>
| Make sure to set database info below
| Avoid leaving this script in a public directory. You can run this anywhere. 
*/
$username = 'user';
$password = 'passwords';
$db = new PDO("mysql:host=localhost;dbname=download", $username, $password);

// generation function
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// checking if number of codes defined
if ($argc != 2) {
    die("Usage: php -f redeem.php <number-of-codes>\n");
} else {
array_shift($argv);

$numberOfCodes = $argv[0];
echo "You have generated $numberOfCodes codes.\n";

for ($k = 0; $k < $numberOfCodes; $k++){
   $code = generateRandomString(12);
   try {
       $codeQuery = "INSERT INTO codes (code, is_used, ip_address, downloads, link)
       VALUES (:code, :is_used, :ip_address, :downloads, :link)";
       $statement = $db->prepare($codeQuery);
       $statement->execute(array(':code' => $code, ':is_used' => 0, ':ip_address' => '10.0.0.43', ':downloads' => 0, ':link' => 'https://google.com/'));
       echo "$code \n";
   } catch (PDOException $ex){
      echo "An error occurred: " .$ex->getMessage();
    }
}

}

?>

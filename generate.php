<?php
/*
| Download Code Generation
| Usage: php -f redeem.php <amount-of-codes>
| Make sure to set database info below
| Avoid leaving this script in a public directory. You can run this anywhere. 
*/
$servername = 'localhost';
$username = 'root';
$password = 'root';
$db = new PDO("mysql:host=$servername;port=8889;dbname=download", $username, $password);

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
    die("Usage: redeem.php <number-of-codes>\n");
} else {
array_shift($argv);

$numberOfCodes = $argv[0];
echo "You have generated $numberOfCodes codes.\n";

for ($k = 0 ; $k < $numberOfCodes; $k++){
   $string = generateRandomString(12);
   try{
       $sqlInsert = "INSERT INTO codes (code)
     VALUES (:code)";
       $statement = $db->prepare($sqlInsert);
       $statement->execute(array(':code' => $string));

       echo "$string \n";
     } catch (PDOException $ex){
          $result = flashMessage("An error occurred: " .$ex->getMessage());
     }
}
}

?>

<?php
//include_once 'parseRedeem.php';
include_once 'utilities.php';
?>
<html>
<head>
  <title>Redeem Your Download</title>
</head>
<body>
  <h1>Redeem Your Download</h1>
  <form action="parseRedeem.php" method="POST">
    <input type="text" name="code" value="Redemption Code"></input>
    <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>"></input>
    <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
    <input type="submit" name="redeemBtn" value="Redeem"></input>
  </form>
</body>
</html>

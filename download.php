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

    $link = $result['link'];
    $downloads = $result['downloads'];

    if($link == $code) {
      if($downloads < 3) {
        $file_name = "file.data";
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        echo "Your download will start momentarily.";

        $select->prepare('SELECT downloads FROM codes WHERE link = :code');
        $select->bindParam(':code', $code, PDO::PARAM_STR);
        $select->execute();
        $bunghole = $select->fetch(PDO::FETCH_ASSOC);

        $downloadCount = $bunghole + 1;

        $update = $db->prepare('UPDATE codes SET downloads = :downloadcount WHERE code = :code');
        $update->bindParam(':code', $code, PDO::PARAM_STR);
        $update->bindParam(':downloadcount', $downloadCount, PDO::PARAM_INT);
        $update-execute();

        echo $downloadCount;

      } else {
        echo "Fuck you";
      }
    } else {
      echo "You have used your 3 downloads. Contact support if you feel this is incorrect.";
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

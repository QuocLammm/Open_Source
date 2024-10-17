<?php
session_start();
error_reporting(0);
include('includes/connectSQL.php');
if (strlen($_SESSION['ccmsaid']==0)) {
  header('location:logout.php');
  } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php include_once('includes/_layoutAdmin.php');?> <!--Kế thừa layout-->
</body>
</html>
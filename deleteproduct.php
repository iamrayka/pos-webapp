<?php

include_once 'connectdb.php';

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {


  header('location:index.php');
}


$id = $_POST['pidd'];


$sql = "delete from tbl_product where pid=$id";

$delete = $pdo->prepare($sql);


if ($delete->execute()) {
} else {

  echo 'Oops! Sth went wrong during deletion process!';
}

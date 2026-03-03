<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/account.php");

  $obj = new Account();
  $obj->deleteAccount($_GET["account_id"]);

  $_SESSION["toastr"] = array("type" => "success", "message" => "Account has been deleted!");
  header("location: index.php");
?>
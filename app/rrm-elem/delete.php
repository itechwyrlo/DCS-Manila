<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/rrm-record.php");

  $obj = new RrmRecord();
  $obj->deleteRecord($_GET["record_id"]);

  $_SESSION["toastr"] = array("type" => "success", "message" => "Record has been deleted!");
  header("location: index.php");
?>
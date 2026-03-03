<?php
  session_start();
  header("Content-Type: application/json; charset=utf-8");

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/queuing.php");

  $category_id = isset($_GET["transaction"]) ? (int)$_GET["transaction"] : 1;
  if ($category_id !== 1 && $category_id !== 2) {
    $category_id = 1;
  }

  $obj = new Queuing();
  $waiting = $obj->getQueues($category_id, 1);
  $nowServingList = $obj->getQueues($category_id, 2);
  $nowServing = !empty($nowServingList) ? $nowServingList[0] : null;

  function formatQueueNo($category_id, $queue_no) {
    $prefix = (int)$category_id === 1 ? "EL" : "HS";
    return $prefix . "-" . str_pad((string)(int)$queue_no, 3, "0", STR_PAD_LEFT);
  }

  $payload = [
    "nowServing" => $nowServing ? [
      "queue_no_display" => formatQueueNo($category_id, $nowServing["queue_no"]),
      "client_name" => $nowServing["client_name"]
    ] : null,
    "waiting" => array_map(function ($item) use ($category_id) {
      return formatQueueNo($category_id, $item["queue_no"]);
    }, $waiting)
  ];

  echo json_encode($payload);

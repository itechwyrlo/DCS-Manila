<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized", "records" => []]);
  exit;
}

include_once __DIR__ . "/../../core/repositories/database.php";
include_once __DIR__ . "/../../core/repositories/num-record.php";

$obj = new NumRecord();
$records = $obj->getRecordsByClass(1);

$out = [];
foreach ($records as $r) {
  $out[] = [
    "record_id" => (int) $r["record_id"],
    "control_no" => $r["control_no"] ?? "",
    "client_name" => $r["client_name"] ?? "",
    "status" => $r["status"] ?? "",
    "personnel" => $r["personnel"] ?? "",
    "created_date" => $r["created_date"] ?? ""
  ];
}
echo json_encode(["records" => $out]);

<?php
/**
 * JSON API for document tracking timeline. Used for real-time sync on the tracking page.
 * No auth required (public tracking by control number).
 */
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$control_no = isset($_GET["control_no"]) ? trim($_GET["control_no"]) : "";
if ($control_no === "") {
  echo json_encode(["error" => "control_no required"]);
  exit;
}

include_once __DIR__ . "/../../core/repositories/database.php";
include_once __DIR__ . "/../../core/repositories/record.php";

$obj = new Record();
$tracks = $obj->getTrackByControlNo($control_no);

if ($tracks === null) {
  echo json_encode(["error" => "Invalid control number", "tracks" => []]);
  exit;
}

// Normalize for JSON (date/time as strings, nulls for empty section)
$out = [];
foreach ($tracks as $item) {
  $out[] = [
    "tracking_id" => (int) $item["tracking_id"],
    "status" => $item["status"],
    "office_code" => $item["office_code"] ?? "",
    "section_code" => $item["section_code"] ?? "",
    "section" => $item["section"] ?? "",
    "remarks" => $item["remarks"] ?? "",
    "date" => $item["date"],
    "time" => $item["time"],
    "created_date" => $item["created_date"] ?? ""
  ];
}

echo json_encode(["tracks" => $out]);

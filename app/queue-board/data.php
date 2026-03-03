<?php
/**
 * Public JSON endpoint for the queue board display.
 * No authentication required so the board can run on a public screen.
 */
if (!defined('APP_ROOT')) {
  $corePaths = __DIR__ . '/../../core/paths.php';
  define('APP_ROOT', file_exists($corePaths) ? dirname(__DIR__, 2) : dirname(__DIR__));
}
require_once file_exists(__DIR__ . '/../../core/paths.php') ? __DIR__ . '/../../core/paths.php' : __DIR__ . '/../core/paths.php';

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$dataDir = APP_ROOT . DIRECTORY_SEPARATOR . 'data';
$flagFile = $dataDir . DIRECTORY_SEPARATOR . "queuing_display.flag";
$displayOn = (file_exists($flagFile) && trim(file_get_contents($flagFile)) === "1");

$windowFile = $dataDir . DIRECTORY_SEPARATOR . "queuing_window.json";
$windows = ["1" => 1, "2" => 2];
if (file_exists($windowFile)) {
  $decoded = @json_decode(file_get_contents($windowFile), true);
  if (is_array($decoded)) {
    if (isset($decoded["1"])) $windows["1"] = (int)$decoded["1"];
    if (isset($decoded["2"])) $windows["2"] = (int)$decoded["2"];
  }
}

$elemWaiting = [];
$elemNowServing = null;
$hsWaiting = [];
$hsNowServing = null;

if ($displayOn) {
  include_once APP_ROOT . '/core/repositories/database.php';
  include_once APP_ROOT . '/core/repositories/queuing.php';
  $obj = new Queuing();
  $elemWaiting = $obj->getQueues(1, 1);
  $elemNowServingList = $obj->getQueues(1, 2);
  $elemNowServing = !empty($elemNowServingList) ? $elemNowServingList[0] : null;
  $hsWaiting = $obj->getQueues(2, 1);
  $hsNowServingList = $obj->getQueues(2, 2);
  $hsNowServing = !empty($hsNowServingList) ? $hsNowServingList[0] : null;
}

function formatQueueNo($category_id, $queue_no) {
  $prefix = (int)$category_id === 1 ? "EL" : "HS";
  return $prefix . "-" . str_pad((string)(int)$queue_no, 3, "0", STR_PAD_LEFT);
}

$payload = [
  "displayOn" => $displayOn,
  "elementary" => [
    "windowNo" => $windows["1"],
    "waiting" => array_map(function ($item) { return formatQueueNo(1, $item["queue_no"]); }, $elemWaiting),
    "nowServing" => $elemNowServing ? formatQueueNo(1, $elemNowServing["queue_no"]) : null
  ],
  "highschool" => [
    "windowNo" => $windows["2"],
    "waiting" => array_map(function ($item) { return formatQueueNo(2, $item["queue_no"]); }, $hsWaiting),
    "nowServing" => $hsNowServing ? formatQueueNo(2, $hsNowServing["queue_no"]) : null
  ]
];

echo json_encode($payload);

<?php
/**
 * Returns the Bootstrap/AdminLTE badge class for a status name (matches dashboards/tracking).
 */
function getStatusBadgeClass($status) {
  if ($status === null || $status === "") return "bg-secondary";
  switch (trim($status)) {
    case "Open":        return "bg-cyan";
    case "In Progress": return "bg-green";
    case "Received":    return "bg-teal";
    case "Forwarded":   return "bg-purple";
    case "Returned":    return "bg-pink";
    case "In Review":   return "bg-indigo";
    case "For Release": return "bg-blue";
    case "Released":    return "bg-dark";
    case "On Hold":     return "bg-red";
    case "Cancelled":   return "bg-gray";
    default:            return "bg-secondary";
  }
}

/**
 * Returns HTML for a status badge with the correct color.
 */
function getStatusBadgeHtml($status) {
  if ($status === null || trim((string)$status) === "") return "—";
  $class = getStatusBadgeClass($status);
  $safe = htmlspecialchars($status);
  return "<span class='badge badge-pill $class'>$safe</span>";
}

<?php
  class AuditLog extends Database {

    /**
     * Data-change / accountability view: who changed what (field-level).
     * Each tracking event is expanded into one row per changed field (Status, Section, Office).
     * Technical & compliance — security & accountability.
     */
    public function getEntries() {
      $conn = $this->connect();

      $sql = "
      SELECT
        rrt.tracking_id,
        rec.control_no,
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) AS personnel,
        rrt.created_date,
        sts.name AS status_name,
        sec.name AS section_name,
        off.name AS office_name
      FROM rrm_record_tracking rrt
      LEFT JOIN record rec ON rrt.record_id = rec.record_id
      LEFT JOIN account acc ON rrt.personnel_id = acc.account_id
      LEFT JOIN status sts ON rrt.status_id = sts.status_id
      LEFT JOIN section sec ON rrt.section_id = sec.section_id
      LEFT JOIN office off ON rrt.office_id = off.office_id
      ORDER BY rrt.created_date DESC, rrt.tracking_id DESC";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $entries = [];
      $seq = 0;
      foreach ($rows as $r) {
        $base = [
          "id" => ++$seq,
          "tracking_id" => $r["tracking_id"],
          "control_no" => $r["control_no"] ?? "",
          "personnel" => trim($r["personnel"] ?? "") ?: null,
          "created_date" => $r["created_date"],
        ];
        if (!empty($r["status_name"])) {
          $entries[] = $base + ["field" => "Status", "new_value" => $r["status_name"]];
        }
        if (!empty($r["section_name"])) {
          $entries[] = $base + ["field" => "Section", "new_value" => $r["section_name"]];
        }
        if (!empty($r["office_name"])) {
          $entries[] = $base + ["field" => "Office", "new_value" => $r["office_name"]];
        }
        if (empty($r["status_name"]) && empty($r["section_name"]) && empty($r["office_name"])) {
          $entries[] = $base + ["field" => "—", "new_value" => "—"];
        }
      }
      return $entries;
    }
  }
?>

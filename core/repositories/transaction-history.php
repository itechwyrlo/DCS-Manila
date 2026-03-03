<?php
  class TransactionHistory extends Database {

    /**
     * Process-flow view: sequence of events (what happened).
     * One row per process step from rrm_record_tracking.
     * Business/domain level — workflow tracking, not field-level changes.
     */
    public function getProcessSteps() {
      $conn = $this->connect();

      $sql = "
      SELECT
        rrt.tracking_id AS id,
        rec.control_no,
        rec.client_name,
        cat.name AS category,
        sts.name AS step,
        sec.name AS section,
        off.name AS office,
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) AS personnel,
        rrt.created_date,
        rrt.remarks
      FROM rrm_record_tracking rrt
      LEFT JOIN record rec ON rrt.record_id = rec.record_id
      LEFT JOIN category cat ON rec.category_id = cat.category_id
      LEFT JOIN status sts ON rrt.status_id = sts.status_id
      LEFT JOIN section sec ON rrt.section_id = sec.section_id
      LEFT JOIN office off ON rrt.office_id = off.office_id
      LEFT JOIN account acc ON rrt.personnel_id = acc.account_id
      ORDER BY rrt.created_date DESC";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }
?>

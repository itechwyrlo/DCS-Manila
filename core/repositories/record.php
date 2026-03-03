<?php
  class Record extends Database {

    public function getTrackByControlNo($control_no) {
      $conn = $this->connect();

      $sql = "
      SELECT
        rrt.tracking_id,
        sts.status_id,
        sts.name as 'status',
        off.office_id,
        off.code as 'office_code',
        off.name as 'office',
        sec.section_id,
        sec.code as 'section_code',
        sec.name as 'section',
        rrt.remarks,
        DATE(rrt.created_date) AS date,
        TIME(rrt.created_date) AS time,
        rrt.created_date
      FROM record as rec
      LEFT JOIN rrm_record_tracking as rrt
        ON rec.record_id = rrt.record_id
      LEFT JOIN status as sts
        ON rrt.status_id = sts.status_id
      LEFT JOIN office as off
        ON rrt.office_id = off.office_id
      LEFT JOIN section as sec
        ON rrt.section_id = sec.section_id
      WHERE rec.control_no = :control_no;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["control_no" => $control_no]);
      $result = $stmt->fetchAll();
      
      if ($stmt->rowCount() <= 0) {
        return null;
      }
      
      return $result;
    }

    public function addRecord($client_name, $control_no, $category_id) {
      $conn = $this->connect();

      $sql = "
      -- Insert record
      INSERT INTO record(client_name, control_no, category_id)
      VALUES
        (:client_name, :control_no, :category_id);";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "client_name" => $client_name, "control_no" => $control_no, "category_id" => $category_id
      ]);
      $result = $conn->lastInsertId();
      
      return $result;
    }
  }
?>
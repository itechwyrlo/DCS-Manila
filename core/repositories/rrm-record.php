<?php
  class RrmRecord extends Database {

    public function getRecordById($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
        rec.record_id,
        rec.client_name,
        rec.control_no,
        que.queue_no,
        cat.category_id,
        cat.name as 'category',
        rec.details,
        sts.status_id,
        sts.name as 'status',
        off.office_id,
        off.name as 'office',
        sec.section_id,
        sec.name as 'section',
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'personnel',
        rec.created_date,
        rrm.documents,
        rrm.released_date,
        rrm.remarks
      FROM rrm_record as rrm
      LEFT JOIN record as rec
        ON rrm.record_id = rec.record_id
      LEFT JOIN queuing as que
        ON rec.record_id = que.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON rrm.status_id = sts.status_id
      LEFT JOIN office as off
        ON rrm.office_id = off.office_id
      LEFT JOIN section as sec
        ON rrm.section_id = sec.section_id
      LEFT JOIN account as acc
        ON rrm.personnel_id = acc.account_id
      WHERE rec.record_id = :id LIMIT 1;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getRecordsByCategory($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
        rec.record_id,
        rec.client_name,
        rec.control_no,
        cat.category_id,
        cat.name as 'category',
        rec.details,
        sts.status_id,
        sts.name as 'status',
        off.office_id,
        off.name as 'office',
        sec.section_id,
        sec.name as 'section',
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'personnel',
        rec.created_date,
        rrm.released_date,
        rrm.remarks
      FROM rrm_record as rrm
      LEFT JOIN record as rec
        ON rrm.record_id = rec.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON rrm.status_id = sts.status_id
      LEFT JOIN office as off
        ON rrm.office_id = off.office_id
      LEFT JOIN section as sec
        ON rrm.section_id = sec.section_id
      LEFT JOIN account as acc
        ON rrm.personnel_id = acc.account_id
      WHERE rec.category_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetchAll();
      
      return $result;
    }

    public function addRecord($id) {
      $conn = $this->connect();

      $sql = "
      INSERT INTO rrm_record(record_id, status_id, office_id, section_id)
      VALUES
        (:id, 1, 1, 1);";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
    }

    public function updateRecord($id, $status_id, $office_id, $section_id, $account_id, $documents, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE rrm_record
      SET
        status_id = :status_id,
        office_id = :office_id,
        section_id = :section_id,
        personnel_id = :account_id,
        documents = :documents,
        remarks = :remarks
      WHERE record_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "office_id" => $office_id, "section_id" => $section_id, "account_id" => $account_id,
        "documents" => $documents, "remarks" => $remarks
      ]);
    }

    public function releaseRecord($id, $status_id, $office_id, $section_id, $account_id, $documents, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE rrm_record
      SET
        status_id = :status_id,
        office_id = :office_id,
        section_id = :section_id,
        personnel_id = :account_id,
        documents = :documents,
        released_date = now(),
        remarks = :remarks
      WHERE record_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "office_id" => $office_id, "section_id" => $section_id, "account_id" => $account_id,
        "documents" => $documents, "remarks" => $remarks
      ]);
    }

    public function setStatus($record_id, $status_id) {
      $conn = $this->connect();
      $sql = "UPDATE rrm_record SET status_id = :status_id WHERE record_id = :record_id;";
      $stmt = $conn->prepare($sql);
      $stmt->execute(["record_id" => $record_id, "status_id" => $status_id]);
    }

    public function deleteRecord($id) {
      $conn = $this->connect();

      $sql = "
      DELETE FROM rrm_record
      WHERE record_id = :id;
      
      DELETE FROM record
      WHERE record_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
    }
  }
?>
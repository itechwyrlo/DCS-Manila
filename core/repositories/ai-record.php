<?php
  class AiRecord extends Database {

    public function getRecordById($id) {
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
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'personnel',
        rec.created_date,
        rrm.documents,
        air.memorandum_no,
        air.released_date,
        air.remarks
      FROM ai_record as air
      LEFT JOIN record as rec
        ON air.record_id = rec.record_id
      LEFT JOIN rrm_record as rrm
        ON rec.record_id = rrm.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON air.status_id = sts.status_id
      LEFT JOIN account as acc
        ON air.personnel_id = acc.account_id
      WHERE rec.record_id = :id LIMIT 1;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getRecords() {
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
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'personnel',
        rec.created_date,
        air.released_date,
        air.remarks
      FROM ai_record as air
      LEFT JOIN record as rec
        ON air.record_id = rec.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON air.status_id = sts.status_id
      LEFT JOIN account as acc
        ON air.personnel_id = acc.account_id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }

    public function hasRecord($record_id) {
      $conn = $this->connect();
      $stmt = $conn->prepare("SELECT 1 FROM ai_record WHERE record_id = :id LIMIT 1");
      $stmt->execute(["id" => $record_id]);
      return $stmt->fetch() !== false;
    }

    public function updateStatus($record_id, $status_id) {
      $conn = $this->connect();
      $stmt = $conn->prepare("UPDATE ai_record SET status_id = :status_id WHERE record_id = :id");
      $stmt->execute(["id" => $record_id, "status_id" => $status_id]);
    }

    public function addRecord($id, $status_id, $memorandum_no) {
      $conn = $this->connect();

      $sql = "
      INSERT INTO ai_record(record_id, status_id, memorandum_no)
      VALUES
        (:id, :status_id, :memorandum_no);";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "status_id" => $status_id, "memorandum_no" => $memorandum_no]);
    }

    public function updateRecord($id, $status_id, $account_id, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE ai_record
      SET
        status_id = :status_id,
        personnel_id = :account_id,
        remarks = :remarks
      WHERE record_id = :id";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "account_id" => $account_id, "remarks" => $remarks
      ]);
    }

    public function releaseRecord($id, $status_id, $account_id, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE ai_record
      SET
        status_id = :status_id,
        personnel_id = :account_id,
        released_date = now(),
        remarks = :remarks
      WHERE record_id = :id";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "account_id" => $account_id, "remarks" => $remarks
      ]);
    }
  }
?>
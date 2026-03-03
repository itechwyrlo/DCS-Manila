<?php
  class Ac201Record extends Database {

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
        acr.school,
        acr.released_date,
        acr.remarks
      FROM ac201_record as acr
      LEFT JOIN record as rec
        ON acr.record_id = rec.record_id
      LEFT JOIN rrm_record as rrm
        ON rec.record_id = rrm.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON acr.status_id = sts.status_id
      LEFT JOIN account as acc
        ON acr.personnel_id = acc.account_id
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
        acr.released_date,
        acr.remarks
      FROM ac201_record as acr
      LEFT JOIN record as rec
        ON acr.record_id = rec.record_id
      LEFT JOIN category as cat
        ON rec.category_id = cat.category_id
      LEFT JOIN status as sts
        ON acr.status_id = sts.status_id
      LEFT JOIN account as acc
        ON acr.personnel_id = acc.account_id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }

    public function hasRecord($record_id) {
      $conn = $this->connect();
      $stmt = $conn->prepare("SELECT 1 FROM ac201_record WHERE record_id = :id LIMIT 1");
      $stmt->execute(["id" => $record_id]);
      return $stmt->fetch() !== false;
    }

    public function updateStatus($record_id, $status_id) {
      $conn = $this->connect();
      $stmt = $conn->prepare("UPDATE ac201_record SET status_id = :status_id WHERE record_id = :id");
      $stmt->execute(["id" => $record_id, "status_id" => $status_id]);
    }

    public function addRecord($id, $status_id) {
      $conn = $this->connect();

      $sql = "
      INSERT INTO ac201_record(record_id, status_id)
      VALUES
        (:id, :status_id);";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "status_id" => $status_id]);
    }

    public function updateRecord($id, $status_id, $account_id, $school, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE ac201_record
      SET
        status_id = :status_id,
        personnel_id = :account_id,
        school = :school,
        remarks = :remarks
      WHERE record_id = :id";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "account_id" => $account_id, "school" => $school, "remarks" => $remarks
      ]);
    }

    public function releaseRecord($id, $status_id, $account_id, $school, $remarks) {
      $conn = $this->connect();

      $sql = "
      UPDATE ac201_record
      SET
        status_id = :status_id,
        personnel_id = :account_id,
        school = :school,
        released_date = now(),
        remarks = :remarks
      WHERE record_id = :id";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "status_id" => $status_id, "account_id" => $account_id, "school" => $school, "remarks" => $remarks
      ]);
    }
  }
?>
<?php
  class RrmRecordTracking extends Database {

    /**
     * @param int $id record_id
     * @param string $remarks
     * @param int|null $personnel_id account_id of user who performed the action (e.g. $_SESSION["account_id"])
     */
    public function addRecord($id, $remarks, $personnel_id = null) {
      $conn = $this->connect();

      $sql = "
      SET @record_id = :id;

      SELECT
        @status_id := status_id,
        @office_id := office_id,
        @section_id := section_id
      FROM rrm_record
      WHERE record_id = @record_id;
    
      INSERT INTO rrm_record_tracking(record_id, status_id, office_id, section_id, personnel_id, remarks)
      VALUES
        (@record_id, @status_id, @office_id, @section_id, :personnel_id, :remarks);";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id,
        "remarks" => $remarks,
        "personnel_id" => $personnel_id,
      ]);
    }
  }
?>
<?php
  class MemorandumNo extends Database {

    public function getMemorandumNoById($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
      memorandum_id,
        CONCAT_WS('-', LPAD(YEAR(CURDATE()), 4, 0), LPAD(counter + 1, 3, 0)) as 'value'
      FROM memorandum_no
      WHERE memorandum_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function updateMemorandumNo($id, $value) {
      $conn = $this->connect();

      $sql = "
      UPDATE memorandum_no
      SET
        counter = counter + 1,
        value = :value
      WHERE memorandum_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "value" => $value]);
    }
  }
?>
<?php
  class ControlNo extends Database {

    public function getControlNoById($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
        control_id,
        CONCAT_WS('-', code, LPAD(YEAR(CURDATE()), 4, 0), LPAD(MONTH(CURDATE()), 2, 0), LPAD(counter + 1, 4, 0)) as 'value'
      FROM control_no
      WHERE control_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function updateControlNo($id, $value) {
      $conn = $this->connect();

      $sql = "
      UPDATE control_no
      SET
        counter = counter + 1,
        value = :value
      WHERE control_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "value" => $value]);
    }
  }
?>
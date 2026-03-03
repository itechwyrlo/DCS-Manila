<?php
  class Office extends Database {

    public function getOfficeByName($name) {
      $conn = $this->connect();

      $sql = "
      SELECT
        office_id,
        code,
        name
      FROM office
      WHERE name = :name;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["name" => $name]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getOffices() {
      $conn = $this->connect();

      $sql = "
      SELECT
        office_id,
        code,
        name
      FROM office;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }
  }
?>
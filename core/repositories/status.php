<?php
  class Status extends Database {

    public function getStatusByName($name) {
      $conn = $this->connect();

      $sql = "
      SELECT
        status_id,
        name
      FROM status
      WHERE name = :name;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["name" => $name]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getStatuses() {
      $conn = $this->connect();

      $sql = "
      SELECT
        status_id,
        name
      FROM status;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }
  }
?>
<?php
  class Role extends Database {

    public function getRoleByName($name) {
      $conn = $this->connect();

      $sql = "
      SELECT
        role_id,
        name
      FROM role
      WHERE name = :name;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["name" => $name]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getRoles() {
      $conn = $this->connect();

      $sql = "
      SELECT
        role_id,
        name
      FROM role;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }
  }
?>
<?php
  class Section extends Database {

    public function getSectionByName($name) {
      $conn = $this->connect();

      $sql = "
      SELECT
        section_id,
        code,
        name
      FROM section
      WHERE name = :name;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["name" => $name]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getSections() {
      $conn = $this->connect();

      $sql = "
      SELECT
        section_id,
        code,
        name
      FROM section;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }
  }
?>
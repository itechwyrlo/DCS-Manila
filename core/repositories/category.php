<?php
  class Category extends Database {

    public function getCategories() {
      $conn = $this->connect();
      $sql = "SELECT category_id, name FROM category ORDER BY category_id;";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
        category_id,
        name
      FROM category
      WHERE category_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      return $result;
    }
  }
?>
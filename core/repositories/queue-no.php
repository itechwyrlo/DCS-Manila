<?php
  class QueueNo extends Database {

    public function getQueueNoByCategory($category_id) {
      $conn = $this->connect();

      $sql = "
      SELECT
        queue_id,
        counter,
        value,
        modified_date
      FROM queue_no
      WHERE category_id = :category_id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["category_id" => $category_id]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function updateQueueNo($id, $counter, $value) {
      $conn = $this->connect();

      $sql = "
      UPDATE queue_no
      SET
        counter = :counter,
        value = :value
      WHERE queue_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "counter" => $counter, "value" => $value]);
    }

    /**
     * Atomically get the next queue number for the category (resets to 1 if new day).
     * Prevents duplicate queue numbers when multiple transactions are added at once.
     */
    public function getNextQueueNo($category_id) {
      $conn = $this->connect();
      $sql = "
      UPDATE queue_no
      SET
        counter = IF(DATE(modified_date) = CURDATE(), counter + 1, 1),
        value = IF(DATE(modified_date) = CURDATE(), counter + 1, 1),
        modified_date = CURDATE()
      WHERE category_id = :category_id;";
      $conn->prepare($sql)->execute(["category_id" => $category_id]);

      $row = $this->getQueueNoByCategory($category_id);
      return $row ? (int) $row["counter"] : 1;
    }
  }
?>
<?php
  class Queuing extends Database {

    public function getQueues($category_id, $status_id) {
      $conn = $this->connect();
      $today = date('Y-m-d');

      $sql = "
      SELECT
        que.queue_no,
        rec.record_id,
        rec.control_no,
        rec.client_name
      FROM queuing AS que
      INNER JOIN record AS rec
        ON que.record_id = rec.record_id
      INNER JOIN rrm_record AS rrm
        ON rec.record_id = rrm.record_id
      WHERE DATE(que.created_date) = :today
        AND rec.category_id = :category_id
        AND rrm.status_id = :status_id
      ORDER BY que.queue_no ASC";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "today" => $today,
        "category_id" => $category_id,
        "status_id" => $status_id
      ]);
      $result = $stmt->fetchAll();

      return $result;
    }

    public function addQueue($id, $queue_no) {
      $conn = $this->connect();

      $sql = "
      INSERT INTO queuing(record_id, queue_no)
      VALUES
        (:id, :queue_no);";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "queue_no" => $queue_no]);
    }
  }
?>
<?php
  class Account extends Database {

    public function getAccountById($id) {
      $conn = $this->connect();

      $sql = "
      SELECT
      acc.account_id,
        acc.first_name,
        acc.middle_name,
        acc.last_name,
        acc.suffix,
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'full_name',
        acc.birthdate,
        acc.contact_no,
        acc.position,
        acc.avatar_path,
        acc.employment_date,
        rol.name as 'role',
        acc.email,
        acc.password,
        acc.created_date
      FROM account as acc
      LEFT JOIN role as rol
        ON acc.role_id = rol.role_id
      WHERE acc.account_id = :id LIMIT 1;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
      $result = $stmt->fetch();
      
      if ($stmt->rowCount() <= 0) {
        return null;
      }
      
      return $result;
    }

    public function getAccountByEmail($email) {
      $conn = $this->connect();

      $sql = "
      SELECT
        acc.account_id,
        acc.first_name,
        acc.middle_name,
        acc.last_name,
        acc.suffix,
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'full_name',
        acc.birthdate,
        acc.contact_no,
        acc.position,
        acc.avatar_path,
        acc.employment_date,
        rol.name as 'role',
        acc.email,
        acc.password,
        acc.created_date
      FROM account as acc
      LEFT JOIN role as rol
        ON acc.role_id = rol.role_id
      WHERE acc.email = :email LIMIT 1;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["email" => $email]);
      $result = $stmt->fetch();
      
      return $result;
    }

    public function getAccounts() {
      $conn = $this->connect();

      $sql = "
      SELECT
        acc.account_id,
        acc.first_name,
        acc.middle_name,
        acc.last_name,
        acc.suffix,
        CONCAT_WS(' ', acc.first_name, acc.middle_name, acc.last_name, acc.suffix) as 'full_name',
        acc.birthdate,
        acc.contact_no,
        acc.position,
        acc.employment_date,
        acc.avatar_path,
        rol.name as 'role',
        acc.email,
        acc.password,
        acc.created_date
      FROM account as acc
      LEFT JOIN role as rol
      ON acc.role_id = rol.role_id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      return $result;
    }

    public function addAccount($first_name, $middle_name, $last_name, $suffix, $birthdate, $contact_no, $position,
      $employment_date, $avatar_path, $role_id, $email, $password) {
      $conn = $this->connect();

      $sql = "
      INSERT INTO account(first_name, middle_name, last_name, suffix, birthdate, contact_no, position, employment_date, 
        avatar_path, role_id, email, password)
      VALUES (:first_name, :middle_name, :last_name, :suffix, :birthdate, :contact_no, :position, :employment_date, 
        :avatar_path, :role_id, :email, :password);";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name, "suffix" => $suffix, 
        "birthdate" => $birthdate, "contact_no" => $contact_no, "position" => $position, "employment_date" => $employment_date,
        "avatar_path" => $avatar_path, "role_id" => $role_id, "email" => $email, "password" => $password
      ]);
    }

    public function updateAccount($id, $first_name, $middle_name, $last_name, $suffix, $birthdate, $contact_no, $position,
      $employment_date, $avatar_path, $role_id, $email) {
      $conn = $this->connect();

      $sql = "
      UPDATE account
      SET
        first_name = :first_name,
        middle_name = :middle_name,
        last_name = :last_name,
        suffix = :suffix,
        birthdate = :birthdate,
        contact_no = :contact_no,
        position = :position,
        employment_date = :employment_date,
        avatar_path = :avatar_path,
        role_id = :role_id,
        email = :email
      WHERE account_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute([
        "id" => $id, "first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name, "suffix" => $suffix, 
        "birthdate" => $birthdate, "contact_no" => $contact_no, "position" => $position, "employment_date" => $employment_date,
        "avatar_path" => $avatar_path, "role_id" => $role_id, "email" => $email
      ]);
    }

    public function updatePassword($id, $password) {
      $conn = $this->connect();

      $sql = "
      UPDATE account
      SET
        password = :password
      WHERE account_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id, "password" => $password]);
    }

    public function deleteAccount($id) {
      $conn = $this->connect();

      $sql = "
      DELETE FROM account 
      WHERE account_id = :id;";

      $stmt = $conn->prepare($sql);
      $stmt->execute(["id" => $id]);
    }
  }
?>
<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class UserFactory
{
    // DB Instance
    private $db;

    /**
     * Create a new UserFactory object
     * @param /PDO $db PDO DB Object
     * @return void
     */
    public function __construct(PDO $db) {
        // Save the $db instance in the object
        $this->db = $db;
    }

    /**
     * Fetch user details out of the DB
     * @param integer $id User id
     * @param string $email User email
     * @return /User Object
     */
    public function getUser($id, $email) {
        // Check if user match can be found in DB
        $query = "SELECT * FROM `au_user` WHERE `id` = :id AND `email` = :email";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Create and return user object
        return new User($row['first_name'], $row['last_name'], $row['email'], $row['status'], $row['id'], $row['password']);
    }

    /**
     * Fetch user details out of the DB using either ID or Email
     *  As both are unique indexes this shouldn't be an issue
     * @param integer $id User id
     * @param string $email User email
     * @return /User Object
     */
    public function getUserByAttribute($id = '', $email = '') {
        // Check if user match can be found in DB
        $query = "SELECT * FROM `au_user` WHERE (`id` = :id AND `id` != '') || (`email` = :email AND `email` != '')";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Double check we haven't returned a missmatch (shouldn't happen but better to be safe)
        if($id != '') {
            if($row['id'] != $id) {
                return FALSE;
            }
        }

        if($email != '') {
            if($row['email'] != $email) {
                return FALSE;
            }
        }

        // Create and return user object
        return new User($row['first_name'], $row['last_name'], $row['email'], $row['status'], $row['id'], $row['password']);
    }
    
    /**
     * Create and save a new user
     * @param string $first_name First name
     * @param string $last name Last name
     * @param string $email Login email
     * @param string $password Login password
     * @return /User object
     */
    public function newUser($first_name, $last_name, $email, $password) {
        $first_name = trim($first_name);
        $last_name = trim($last_name);
        $email = trim($email);

        // Create salt
        $hashPW = password_hash($password, PASSWORD_BCRYPT);

        // Save to DB
        $query = "  INSERT INTO `au_user`
                    (
                        `first_name`,
                        `last_name`,
                        `email`,
                        `password`,
                        `create_date`,
                        `status`
                    )
                    VALUES
                    (
                        :first_name,
                        :last_name,
                        :email,
                        :password,
                        NOW(),
                        '2'
                    )";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashPW, PDO::PARAM_STR);
            $stmt->execute();

        // Return User object
        return $this->getUser($this->db->lastInsertId(), $email);
    }
}
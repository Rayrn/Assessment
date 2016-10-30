<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class CriteriaFactory
{
    // DB Instance
    private $db;

    /**
     * Create a new CriteriaFactory object
     * @param /PDO $db PDO DB Object
     * @return void
     */
    public function __construct(PDO $db) {
        // Save the $db instance in the object
        $this->db = $db;
    }

    /**
     * Fetch Criteria details out of the DB
     * @param integer $id Criteria id
     * @param string $title Criteria title
     * @return /Criteria Object
     */
    public function getCriteria($id) {
        // Check if Criteria match can be found in DB
        $query = "SELECT * FROM `au_criteria` WHERE `id` = :id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Create and return Criteria object
        return new Criteria($row['title'], $row['status'], $row['id'], $row['create_by']);
    }

    /**
     * Fetch a list of all criteria setup by the user from the DB
     * @param /User $user Current user
     * @param string $status 'Active', 'Saved', 'All'
     * @return /Criteria[]
     */
    public function getCriteriaByUser(User $user, $status = 'active') {
        // Check if Criteria match can be found in DB
        $query = "SELECT `id` FROM `au_criteria` WHERE `create_by` = :user_id";
        switch(strtolower($status)) {
            case 'active': $query .= " AND `status` = '2' "; break;
            case 'saved': $query .= " AND (`status` = '2' OR `status` = '3') "; break;
            case 'all': break;
        }

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        $results = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $this->getCriteria($row['id']);
        }

        // Return Criteria array
        return $results;
    }
    
    /**
     * Create and save a new Criteria
     * @param string $title Title
     * @param /User $user Current user
     * @return /Criteria object
     */
    public function newCriteria($title, User $user) {
        // Save to DB
        $query = "  INSERT INTO `au_criteria`
                    (
                        `title`,
                        `status`,
                        `create_by`
                    )
                    VALUES
                    (
                        :title,
                        '2',
                        :user_id
                    )";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_STR);
            $stmt->execute();

        // Return Criteria object
        return $this->getCriteria($this->db->lastInsertId());
    }
    
    /**
     * Update a new Criteria
     * @param /Criteria $criteria Target
     * @param /User $user Current user
     * @return /Criteria object
     */
    public function updateCriteria(Criteria $criteria, User $user) {
        // Save to DB
        $query = "  UPDATE  `au_criteria`
                    SET     `title` = :title,
                            `status` = :status
                    WHERE   `id` = :id
                    AND     `create_by` = :user_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $criteria->title, PDO::PARAM_STR);
            $stmt->bindParam(':status', $criteria->status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $criteria->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return Criteria object
        return $this->getCriteria($criteria->id);
    }
}
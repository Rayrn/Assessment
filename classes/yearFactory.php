<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class YearFactory
{
    // DB Instance
    private $db;

    /**
     * Create a new YearFactory object
     * @param /PDO $db PDO DB Object
     * @return void
     */
    public function __construct(PDO $db) {
        // Save the $db instance in the object
        $this->db = $db;
    }

    /**
     * Fetch Year details out of the DB
     * @param integer $id Year id
     * @param string $title Year title
     * @return /Year Object
     */
    public function getYear($id) {
        // Check if Year match can be found in DB
        $query = "SELECT * FROM `au_year` WHERE `id` = :id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Create and return Year object
        return new Year($row['title'], $row['status'], $row['id'], $row['create_by']);
    }

    /**
     * Fetch a list of all year setup by the user from the DB
     * @param /User $user Current user
     * @param string $status 'Active', 'Saved', 'All'
     * @return /Year[]
     */
    public function getYearByUser(User $user, $status = 'active') {
        // Check if Year match can be found in DB
        $query = "SELECT `id` FROM `au_year` WHERE `create_by` = :user_id";
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
            $results[] = $this->getYear($row['id']);
        }

        // Return Year array
        return $results;
    }
    
    /**
     * Create and save a new Year
     * @param string $title Title
     * @param /User $user Current user
     * @return /Year object
     */
    public function newYear($title, User $user) {
        // Save to DB
        $query = "  INSERT INTO `au_year`
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

        // Return Year object
        return $this->getYear($this->db->lastInsertId());
    }
    
    /**
     * Update a Year Object
     * @param /Year $year Target
     * @param /User $user Current user
     * @return /Year object
     */
    public function updateYear(Year $year, User $user) {
        // Save to DB
        $query = "  UPDATE  `au_year`
                    SET     `title` = :title,
                            `status` = :status
                    WHERE   `id` = :id
                    AND     `create_by` = :user_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $year->title, PDO::PARAM_STR);
            $stmt->bindParam(':status', $year->status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $year->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return Year object
        return $this->getYear($year->id);
    }
}
<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class BoundryLimitFactory
{
    // DB Instance
    private $db;

    /**
     * Create a new BoundryFactory object
     * @param /PDO $db PDO DB Object
     * @return void
     */
    public function __construct(PDO $db) {
        // Save the $db instance in the object
        $this->db = $db;
    }

    /**
     * Fetch BoundryLimit details out of the DB
     *  Limit data is not avaliable from this method as we have to look it up on a per user basis
     * @param integer $id BoundryLimit id
     * @return /BoundryLimit Object
     */
    public function getBoundryLimit($id) {
        // Check if BoundryLimit match can be found in DB
        $query = "SELECT * FROM `au_boundrylimit` WHERE `id` = :id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Return BoundryLimit object
        return new BoundryLimit($row['boundry_id'], $row['year_id'], $row['lower_limit'], $row['upper_limit'], $row['status'], $row['id'], $row['create_by']);
    }

    /**
     * Fetch a list of all BoundryLimits setup by the user from the DB
     * @param /User $user Current user
     * @return /BoundryLimit[]
     */
    public function getBoundryLimitByUser(User $user) {
        // Check if BoundryLimit match can be found in DB
        $query = "SELECT `id` FROM `au_boundrylimit` WHERE `create_by` = :user_id AND `status` = '2'";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        $results = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Add to results
            $results[] = $this->getBoundryLimit($row['id']);
        }

        // Return BoundryLimit array
        return $results;
    }

    /**
     * Fetch the BoundryLimit set by the user from the DB matching a specific boundry and year
     * @param /Boundry $boundry
     * @param /Year $year
     * @param /User $user Current user
     * @return /BoundryLimit[]
     */
    public function getBoundryLimitByAll(Boundry $boundry, Year $year, User $user) {
        // Check if BoundryLimit match can be found in DB
        $query = "SELECT `id` FROM `au_boundrylimit` WHERE `boundry_id` = :boundry_id AND `year_id` = :year_id AND `create_by` = :user_id AND `status` = '2'";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':boundry_id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':year_id', $year->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Return BoundryLimit array
        return $this->getBoundryLimit($row['id']);
    }

    public function newBoundryLimit(Boundry $boundry, Year $year, $lower_limit, $upper_limit, User $user) {
        // First we need to delete all the old ones
        // Save to DB
        $query = "  INSERT INTO `au_boundrylimit`
                    (
                        `boundry_id`,
                        `year_id`,
                        `lower_limit`,
                        `upper_limit`,
                        `status`,
                        `create_by`
                    )
                    VALUES
                    (
                        :boundry_id,
                        :year_id,
                        :lower_limit,
                        :upper_limit,
                        '2',
                        :user_id
                    )";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':boundry_id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':year_id', $year->id, PDO::PARAM_INT);
            $stmt->bindParam(':lower_limit', $lower_limit, PDO::PARAM_INT);
            $stmt->bindParam(':upper_limit', $upper_limit, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        $insert_id = $this->db->lastInsertId();

        // Now we need to destroy the old ones
        $query = "  UPDATE  `au_boundrylimit`
                    SET     `status` = '7'
                    WHERE   `boundry_id` = :boundry_id
                    AND     `year_id` = :year_id
                    AND     `create_by` = :user_id
                    AND     `status` != '7'
                    AND     `id` != :insert_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':boundry_id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':year_id', $year->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindParam(':insert_id', $insert_id, PDO::PARAM_INT);
            $stmt->execute();

        // Return BoundryLimit object
        return $this->getBoundryLimit($insert_id);
    }
    
    /**
     * Update a BoundryLimit
     * @param /BoundryLimit $boundrylimit Target
     * @param /User $user Current user
     * @return /BoundryLimit object
     */
    public function updateBoundryLimit(BoundryLimit $boundryLimit, User $user) {
        // Save to DB
        $query = "  UPDATE  `au_boundrylimit`
                    SET     `lower_limit` = :lower_limit,
                            `upper_limit` = :upper_limit,
                            `status` = :status
                    WHERE   `id` = :id
                    AND     `create_by` = :user_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lower_limit', $boundryLimit->lower_limit, PDO::PARAM_INT);
            $stmt->bindParam(':upper_limit', $boundryLimit->upper_limit, PDO::PARAM_INT);
            $stmt->bindParam(':status', $boundryLimit->status, PDO::PARAM_INT);
            $stmt->bindParam(':id', $boundryLimit->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return BoundryLimit object
        return $this->getBoundryLimit($boundryLimit->id);
    }
}
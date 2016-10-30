<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class BoundryFactory
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

        // Setup the year factory
        $this->yearFactory = new YearFactory($db);
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
        return new BoundryLimit($row['boundry_id'], $row['year_id'], $row['upper_limit'], $row['status'], $row['id'], $row['create_by'])
    }

    /**
     * Fetch a list of all boundry setup by the user from the DB
     * @param /User $user Current user
     * @param string $status 'Active', 'Saved', 'All'
     * @return /BoundryLimit[]
     */
    public function getBoundryLimitByUser(User $user) {
        // Check if BoundryLimit match can be found in DB
        $query = "SELECT `id` FROM `au_boundrylimit` WHERE `create_by` = :user_id";

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
     * Create and save a new BoundryLimit
     * @param string $grouping Grouping value (L=1, M=2, H=3 => SUM)
     * @param string $title Title
     * @param /User $user Current user
     * @return /BoundryLimit object
     */
    public function newBoundryLimit(Boundry $boundry, Year $year, $upper_limit, User $user) {
        // Save to DB
        $query = "  INSERT INTO `au_boundrylimit`
                    (
                        `boundry_id`,
                        `year_id`,
                        `upper_limit`,
                        `status`,
                        `create_by`
                    )
                    VALUES
                    (
                        :boundry_id,
                        :year_id,
                        :upper_limit,
                        '2',
                        :user_id
                    )";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':boundry_id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':year_id', $year->id, PDO::PARAM_INT);
            $stmt->bindParam(':upper_limit', $upper_limit, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return BoundryLimit object
        return $this->getBoundry($this->db->lastInsertId());
    }
    
    /**
     * Update a new BoundryLimit
     * @param /BoundryLimit $boundrylimit Target
     * @param /User $user Current user
     * @return /BoundryLimit object
     */
    public function updateBoundryLimit(BoundryLimit $boundrylimit, User $user) {
        // Save to DB
        $query = "  UPDATE  `au_boundry`
                    SET     `upper_limit` = :upper_limit,
                            `status` = :status
                    WHERE   `id` = :id
                    AND     `create_by` = :user_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':upper_limit', $boundry->upper_limit, PDO::PARAM_STR);
            $stmt->bindParam(':status', $boundry->status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return BoundryLimit object
        return $this->getBoundryLimit($boundrylimit->id);
    }
}
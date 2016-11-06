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
     * Fetch Boundry details out of the DB
     *  Limit data is not avaliable from this method as we have to look it up on a per user basis
     * @param integer $id Boundry id
     * @return /Boundry Object
     */
    public function getBoundry($id) {
        // Check if Boundry match can be found in DB
        $query = "SELECT * FROM `au_boundry` WHERE `id` = :id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Return Boundry object
        return new Boundry($row['grouping'], $row['title'], $row['status'], $row['id'], $row['create_by']);;
    }

    /**
     * Fetch a list of all boundry setup by the user from the DB
     * @param /User $user Current user
     * @param string $status 'Active', 'Saved', 'All'
     * @return /Boundry[]
     */
    public function getBoundryByUser(User $user) {
        // Check if Boundry match can be found in DB
        $query = "SELECT `id` FROM `au_boundry` WHERE `create_by` = :user_id";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        $results = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Add to results
            $results[] = $this->getBoundry($row['id']);
        }

        // Return Boundry array
        return $results;
    }

    /**
     * Fetch a list of all boundry setup by the user from the DB
     * @param /User $user Current user
     * @param string $status 'Active', 'Saved', 'All'
     * @return /Boundry[]
     */
    public function getBoundryByGrouping(User $user, $grouping) {
        // Check if Boundry match can be found in DB
        $query = "SELECT `id` FROM `au_boundry` WHERE `create_by` = :user_id AND `grouping` = :grouping";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindParam(':grouping', $grouping, PDO::PARAM_INT);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return FALSE;
        }

        // Return Boundry array
        return $this->getBoundry($row['id']);
    }
    
    /**
     * Create and save a new Boundry
     * @param string $grouping Grouping value (L=1, M=2, H=3 => SUM)
     * @param string $title Title
     * @param /User $user Current user
     * @return /Boundry object
     */
    public function newBoundry($title, User $user) {
        // Save to DB
        $query = "  INSERT INTO `au_boundry`
                    (
                        `grouping`,
                        `title`,
                        `status`,
                        `create_by`
                    )
                    VALUES
                    (
                        :grouping,
                        :title,
                        '2',
                        :user_id
                    )";

        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':grouping', $grouping, PDO::PARAM_STR);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_STR);
            $stmt->execute();

        // Return Boundry object
        return $this->getBoundry($this->db->lastInsertId());
    }
    
    /**
     * Update a new Boundry
     * @param /Boundry $boundry Target
     * @param /User $user Current user
     * @return /Boundry object
     */
    public function updateBoundry(Boundry $boundry, User $user) {
        // Save to DB
        $query = "  UPDATE  `au_boundry`
                    SET     `title` = :title,
                            `status` = :status
                    WHERE   `id` = :id
                    AND     `create_by` = :user_id";
        $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $boundry->title, PDO::PARAM_STR);
            $stmt->bindParam(':status', $boundry->status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $boundry->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

        // Return Boundry object
        return $this->getBoundry($boundry->id);
    }
}
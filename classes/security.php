<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class Security
{
    /************************
     * User Login Functions *
     ************************/
    /**
     * Check if user is authorised
     * @param /PDO $db Database connection
     * @return FALSE or /User Object
     */
    public static function isAuth(PDO $db) {
        // Assume failure
        $auth_user = FALSE;

        // Load user details (if found)
        $sess_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : FALSE;
        $sess_user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : FALSE;

        // Check if user is a) logged in and b) active
        if($sess_user_id && $sess_user_email) {
            // Check if user match can be found in DB
            $userFactory = new UserFactory($db);
            $user = $userFactory->getUser($sess_user_id, $sess_user_email);

            // Check if user is valid and if they have timed out
            if($user && Security::userTimeout($db, $user)) {
                // All OK
                $auth_user = $user;
            }
        }

        if(!$auth_user) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);

            return FALSE;
        } else {
            return $auth_user;
        }
    }

    /**
     * Check if user has timed out
     * @param /PDO $db Database connection
     * @param /User $user User object 
     * @return boolean true if valid session, false if timed out
     */
    public static function userTimeout(PDO $db, User $user) {
        $session_id = session_id();

        // Check if user has timed out
        $query = "  SELECT  `last_active`
                    FROM    `au_sessionlog`
                    WHERE   `user_id` = :user_id
                    AND     `session_id` = :session_id
                    ORDER BY `last_active` DESC";
        $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
            $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            // Session/User not found
            return FALSE;
        } else {
            if(strtotime(TIMEOUT_MINS.' minutes', strtotime($row['last_active'])) < time()) {
                // Timed out
                return FALSE;
            }
        }
            
        return TRUE;
    }

    /**
     * Update the session log
     * @param /PDO $db Database connection
     * @param /User $user User object 
     * @return void
     */
    public static function updateSession(PDO $db, User $user) {
        $session_valid = Security::userTimeout($db, $user);
        $session_id = session_id();

        var_dump($session_valid);

        if($session_valid) {
            // Update last active point for prior session
            $query = "  UPDATE  `au_sessionlog`
                        SET     `last_active` = NOW()
                        WHERE   `user_id` = :user_id
                        AND     `session_id` = :session_id
                        ORDER BY `last_active` DESC
                        LIMIT   1";
            $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
                $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                $stmt->execute();
        } else {
            // Insert new record
            $query = "  INSERT INTO `au_sessionlog`
                        (
                            `user_id`,
                            `session_id`,
                            `last_active`
                        )
                        VALUES
                        (
                            :user_id,
                            :session_id,
                            NOW()
                        )";

            $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
                $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                $stmt->execute();
        }
    }
}
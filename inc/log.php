<?php
/* Performance Logging */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

$script_end = microtime(true); // Use floating point notation

// Convert to DB variables where needed
$session_id = session_id();
$script_start_db = date('Y-m-d H:i:s', $script_start);
$script_end_db = date('Y-m-d H:i:s', $script_end);
$script_load_time = round(($script_end - $script_start) * 1000);

// Save to DB
$query = "  INSERT INTO `serverlog`
            (
                `request_uri`,
                `query_string`,
                `session_id`,
                `script_start_time`,
                `script_end_time`,
                `script_load_time`
            )
            VALUES
            (
                :request_uri,
                :query_string,
                :session_id,
                :script_start_time,
                :script_end_time,
                :script_load_time
            )";
$stmt = $pdo->prepare($query);
    $stmt->bindParam(':request_uri', $_SERVER['REQUEST_URI'], PDO::PARAM_STR);
    $stmt->bindParam(':query_string', $_SERVER['QUERY_STRING'], PDO::PARAM_STR);
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt->bindParam(':script_start_time', $script_start_db, PDO::PARAM_STR);
    $stmt->bindParam(':script_end_time', $script_end_db, PDO::PARAM_STR);
    $stmt->bindParam(':script_load_time', $script_load_time, PDO::PARAM_INT);
    $stmt->execute();
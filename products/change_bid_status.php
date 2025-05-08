<?php

require_once '../includes/DB-con.php';
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Update items where END_DATE (START_DATE + PERIOD_OF_BID days) has passed
$sql = "UPDATE ITEM 
        SET STATUS = 'ended' 
        WHERE STATUS != 'ended' 
        AND DATE_ADD(START_DATE, INTERVAL PERIOD_OF_BID DAY) < NOW()";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Item statuses updated successfully",
        "affected_rows" => $conn->affected_rows
    ]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>

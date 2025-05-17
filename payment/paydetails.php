<?php 
header('Content-Type: application/json'); // Set response type to JSON

echo json_encode([

            "upload" => 150,
            "uploadFee" => 10,
            "enrollFee" => 20,
            "payFee" => 5,
            "BidFee" => 5

]);
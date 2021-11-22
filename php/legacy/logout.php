<?php 
//Written by NamHyeok Kim
    session_start();
    session_destroy();

    header("Content-Type: application/json");

    echo json_encode(true, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
?>
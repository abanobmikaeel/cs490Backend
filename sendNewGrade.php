<?php
    /*
    testing
    
    curl -d '{"examId":"93", "studentId":"4444", "newGrade":"200", "questionId":"122"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/sendNewGrade.php    
    */
    include_once "DatabaseHandler.php";
    $success = false;
    
    $json = json_decode(file_get_contents('php://input'), true);
    
    $studentId   = $json["studentId"];
    $examId      = $json["examId"];
    $grade       = $json["newGrade"];
    $questionId  = $json["questionId"];
    
    $success = manuallyChangeGrade($studentId, $examId, $grade, $questionId);
    
    $array = array(
        "success"     =>  $success,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);  
?>

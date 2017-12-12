<?php
    /*
    testing
    
     curl -d '{"studentID":5555, "examID": 127}'  -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/finalGrade.php
    */
    include_once "DatabaseHandler.php";

    $json = json_decode(file_get_contents('php://input'), true);
   
    $studentId  = $json["studentID"];
    $examId     = $json["examID"];
    
    //echo $studentId;
    //echo $examId;

    $result = finalGrade($studentId, $examId);

    $array = array(
        "success" => $result,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

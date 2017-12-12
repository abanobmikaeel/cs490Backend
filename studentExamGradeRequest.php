<?php
    /*
    testing
    
    curl -d '{"examID":"114", "studentID":"4444"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/studentExamGradeRequest.php    
    */
    include_once "DatabaseHandler.php";
    
    $json = json_decode(file_get_contents('php://input'), true);
    
    $studentId   = $json["studentID"];
    $examId      = $json["examID"];
    
    $questions = getDetailedExamAnswers($studentId, $examId);
    
    $array = array(
        "questions"     =>  $questions,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);  
?>

<?php
    /*
    testing
    
    curl -d '{"examID":"45", "studentId":"4444", "questionId": "89", "comment":"good stuff"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/sendComment.php    
    */
    include_once "DatabaseHandler.php";
    $success = false;
    
    $json = json_decode(file_get_contents('php://input'), true);
    
    $studentId   = $json["studentId"];
    $examId      = $json["examId"];
    $questionId  = $json["questionId"];
    $comment     = $json["comment"];
    
    $success = addComment($studentId, $examId, $questionId, $comment);
    
    $array = array(
        "success"     =>  $success,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);  
?>

<?php
    /*
    testing
    
    curl -d '{"studentId":"3333"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/viewAvailableExams.php    
    */
    include_once "DatabaseHandler.php";
    include_once "QueryParser.php";

    $json = json_decode(file_get_contents('php://input'), true);

    $examIds = getStudentsExamsUsing($json["studentId"]);
    
    $examArray = getExamListFrom($examIds);
    
    $array = array(
        "exams" => $examArray,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);  
?>

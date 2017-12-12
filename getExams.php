<?php
    /*
    testing
    
    curl -d '{"test":"test"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/getExams.php
    */
    
    include_once "DatabaseHandler.php";

    //echo getQuestionUsing(12);
    $examList = getExams();
    
    $array = array(
        "exams" => $examList,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

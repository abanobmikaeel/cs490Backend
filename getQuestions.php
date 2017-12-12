<?php
    // This value is false by default since we should return false if we couldnt reach the database
    //
     
    /*testing*/
    // curl -d '{"test":"test"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/getQuestions.php
    include_once "DatabaseHandler.php";

    $questionList = getQuestions();
    
    $array = array(
        "questions" => $questionList,
    );
       

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

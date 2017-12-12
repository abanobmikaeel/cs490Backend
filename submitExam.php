<?php
    /*  
    testing
   
    curl -d '{"studentId":"3333", "examId":"14"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/submitExam.php
    */

    include_once "DatabaseHandler.php";

    $json  = json_decode(file_get_contents('php://input'), true);

    $studentId          =  $json["studentId"];
    $examId             =  $json["examId"];
    
    $result = submitExam($studentId, $examId);

    $array = array(
	    "success" => $result,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

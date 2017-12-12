<?php
    /*  
    testing
   
    curl -d '{ "studentID":"5555", "examID":"121", "questionID": "139",  "studentAnswer": "trying to test", "score": "100", "comments": "testing comments"}'  -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/submitGrade.php
    
    */

    include_once "DatabaseHandler.php";

    $json  = json_decode(file_get_contents('php://input'), true);

    $studentId          =  $json["studentID"];
    $examId             =  $json["examID"];
    $questionId         =  $json["questionID"];
    
    $studentAnswer      =  $json["studentAnswer"];
    $pointsEarned       =  $json["score"];
    $graderComments     =  $json["comments"];

    $result = submitGrade($studentId, $examId, $questionId, $studentAnswer, $pointsEarned, $graderComments);

    $array = array(
	    "success" => $result,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

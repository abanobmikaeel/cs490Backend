<?php
    /*
    testing
    
    curl -d '{"examId":"113"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/getExamQuestions.php    
    */
    include_once "DatabaseHandler.php";
    include_once "QueryParser.php";
    
    $json = json_decode(file_get_contents('php://input'), true);
    
    $exam = getExamUsing($json["examId"]);
    
    //var_dump ($exam);
    
    $questionList = getQuestionListFrom($exam["questionList"], $json["examId"]);
   
    
    $array = array(
        "examName"     =>  $exam["testName"],
    	"examId"       => $json["examId"],
    	"questionList" => $questionList,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);  
?>

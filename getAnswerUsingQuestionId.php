<?php     
    /*
    testing
    
    curl -d '{"questionID":"137", "examID":"121"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/getAnswerUsingQuestionId.php
    */
    include_once "DatabaseHandler.php";
    include_once "QueryParser.php";

    $json = json_decode(file_get_contents('php://input'), true);
    $idArray =  convertQuestionsString();
    $questionID = $json["questionID"];
    $examID = $json["examID"];

    $returnableArray = array();
    $caseArray = array();
   
    $testCaseList = getTestCaseIds($json["questionID"]);

    $answer = getAnswerUsing($json["questionID"]);

    $tableRow["functionName"]       = $answer["functionName"];
    $tableRow["requirement"]        = $answer["requirement"];
    $tableRow["points"]     = getQuestionGrade($questionID, $examID);
    $tableRow["testCaseDT"]         = $answer["testCaseDT"];

    foreach ($testCaseList as $testCase){
        $tableR["testCases"] = $testCase["testCases"];
        $tableR["expectedOutput"] = $testCase["expectedOutput"];

        array_push($caseArray, $tableR);
    }
    
    $tableRow["inputOutput"] = $caseArray;

    array_push($returnableArray, $tableRow);

    echo json_encode($returnableArray, JSON_PRETTY_PRINT);
?>

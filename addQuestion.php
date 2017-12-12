 <?php
   /*
    curl -d '{  "questionText": "Question for Turtle",  "expectedReturn": "45,43,22", "functionName": "test", "difficulty":"easy", "type": "for", "testCase": [{ "case": "+, 1, 2", "expectedReturn": "test"}]}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/addQuestion.php
    */
   include_once "DatabaseHandler.php";


   $questionJson     = json_decode(file_get_contents('php://input'), true);
       //var_dump($questionJson);

   $functionName     = $questionJson["functionName"];
   $questionText     = $questionJson["questionText"];
   $testCaseDT       = $questionJson["dataType"];
   $requirements     = $questionJson["requirements"];   
   $difficulty       = $questionJson["difficulty"];
   $testCaseList     = $questionJson["testCase"];
   $type             = $questionJson["qType"];
   
   $result = addQuestion($questionText, $testCaseList, $functionName, $testCaseDT, $difficulty, $type, $requirements);

   $array = array(
        "success" => $result,
   );

   echo json_encode($array, JSON_PRETTY_PRINT);
?>

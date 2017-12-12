<?php
    /*  
    testing
   
    curl -d '{"examName": "test exam", "questionList": [{"questionID": "88","points": "10"},{"questionID": "90","points": "20"}]}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/addExam.php
    */

   include_once "DatabaseHandler.php";

   $examJson  = json_decode(file_get_contents('php://input'), true);
   
   $examName      = $examJson["examName"];
   $questionList  = $examJson["questionList"];
   
   $result = addExam($examName, $questionList);

   $array = array(
    	"success" => $result,
   );

   echo json_encode($array, JSON_PRETTY_PRINT);
?>

 <?php
    /**
        This function takes an exam "QuestionIds' field and returns them in an Integer[] form
    
        @param (array) takes an exam
        @returns (array) an array of questions 
    */
    
    function convertQuestionsString($stringExam) {
        $questionListString = trim($stringExam ,'[]');
        $questionListArray = array_map('intval', explode(',', $questionListString));
        
        return $questionListArray;
    }     
    
    /**
        This function takes an array of question IDs and fetches them from the question database and returns the actual questions
        
        @param (array) takes id array
        @returns (array) an array of questions 
    */
    function getQuestionListFrom($idArray, $examId) {
        $returnable = array();
        $questionListArray = convertQuestionsString($idArray);
        
        foreach ($questionListArray as $questionId) {
                $questionJson = getQuestionUsing($questionId, $examId);
               
                /// var_dump($questionJson);
                
                $questionArray["questionText"] = $questionJson[0];
                $questionArray["questionId"] = $questionJson[1];
                $questionArray["points"]    = $questionJson["2"];
                
                array_push($returnable, $questionArray);
        }
           return $returnable;
    }
    
    function getExamListFrom($idList) {
       $returnable = array();
       $examListArray = convertQuestionsString($idList);
       
        foreach ($examListArray as $Id) {
                $examJson = getExamUsing($Id);
                $examArray["testName"] = $examJson["testName"];
                $examArray["questionList"] = $examJson["questionList"];
                $examArray["examId"] = $Id;
                
                array_push($returnable, $examArray);
        }
           return $returnable;
    }  
    
    function questionIdsFromDictionary($dictionary) {
        $returnableString = "";
        
        foreach ($dictionary as $question) {
        
            if ($question === end($dictionary)){
                $returnableString  .= $question["questionID"];
                break;
            }

            $returnableString  .= $question["questionID"] .",";
    
        }
        return $returnableString;
    }
?>

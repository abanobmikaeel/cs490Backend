<?php
    
    include_once "QueryParser.php";
    /*
        connect to database.
    */
    function connect() { //function parameters, two variables.
        $connection = mysql_connect("sql.njit.edu", "ahm28", "bangle75", "ahm28");
        mysql_query("use ahm28");
        
        if ($connection) { 
	        return $connection;
        }
        // If we cannot connect to db
        //
        if (!$connection) {
            die('Could not connect: ' . mysql_error());
        }
    }
    
    /**
        sends query
        returns response
       
    */
    function sendSQLQuery($QueryString) {
        $link = connect();
        $QueryResponse = mysql_query($QueryString);
        mysql_close($link);
        return $QueryResponse;
    }

    /*
        Login the user and see if this is valid.
    */
    function loginUser($username, $password) {
        $hashedPassword = hash('sha1', $password);
        $result = sendSQLQuery("SELECT userType, userId FROM UserTable WHERE username='$username' and password='$hashedPassword'");
        if (!$result) {
            die('Invalid query:' .mysql_error());
        } 
        return $result;
    }
    
    /*
        Add a question to the database.
    */
    function addQuestion( $questionText, $testCaseList, $functionName, $testCaseDT, $difficulty, $type, $requirements) {
       
       // Add test cases first and get their ID then use add testcaseId to the actual question 
       
       $testCaseIdList = addTestcases($testCaseList);
        
       $result = sendSQLQuery("INSERT INTO `ahm28`.`QuestionTable` (`questionText`, `test_case_id_list`, `functionName`, `testCaseDT`, `difficulty`, `type`, `requirement`) VALUES ('$questionText', '$testCaseIdList','$functionName', '$testCaseDT', '$difficulty', '$type', '$requirements' )");       
       
        if (!$result) {
            die('Invalid query:' .mysql_error());
            return "false";
        } 
        return "true";  
    }
    
    /*
        Sends back an array with all questions from database.
    */
    function getQuestions() {
        $result = sendSQLQuery("SELECT * FROM `QuestionTable`");

        if (!$result) {
            die('Invalid query:' .mysql_error());
        }
        
        $questionList = array();
        
        // This loop fetches the array parts from the query associatively, and adds them to an array. 
        while ($questionRows = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $table_row['questionId'] = $questionRows['questionId'];
            $table_row['questionText'] = $questionRows['questionText'];
            $table_row['difficulty'] = $questionRows['difficulty'];
            $table_row['type'] = $questionRows['type'];
            

            array_push($questionList, $table_row);
        }
        return $questionList;
    }
    
    /*
        Adds an exam to the database.
    */
    function addExam($examName, $questionList) {
        
        // convert questionalist to a string with all the ids
        $questionIdList = questionIdsFromDictionary($questionList);
    
        $result = sendSQLQuery("INSERT INTO `ahm28`.`ExamTable` (`testName`, `questionList`) VALUES ('$examName', '$questionIdList')");
        
        $result2 = sendSQLQuery("SELECT testId FROM ExamTable ORDER BY testId DESC LIMIT 1");

        $num = mysql_fetch_array($result2, MYSQL_ASSOC);
        
        $examId = $num["testId"];
        
        foreach ($questionList as $question) {
            $questionId      = $question["questionID"];
            $questionPoints  = $question["points"]; 
        
            $result3 = sendSQLQuery("INSERT INTO `ExamQuestionPontsTable` (`examID`, `questionId`, `questionPoints`) VALUES ('$examId', '$questionId', '$questionPoints')");
    
        }
        
        assignExamToAll($examId);
        
        if (!$result) {
            die('Invalid query:' .mysql_error());
            return "false";
        }
        
        return "true";
    }
    
    
    /*
        returns an array of exams
    */
    function getExams() {
        $returnableArray = array();
        
        $result = sendSQLQuery("SELECT * FROM `ExamTable`");
        
        // This loop fetches the array parts from the query associatively, and adds them to an array. 
        while ($examRow = mysql_fetch_array($result, MYSQL_ASSOC)) {           
            $table_row['testId'] = $examRow['testId'];
            $table_row['testName'] = $examRow['testName'];
            $table_row ['questionList'] =  $examRow['questionList'];
            array_push($returnableArray, $table_row);
        }
        
        return $returnableArray;
    }
    
    /*
        returns a question using its ID.
    */
    function getQuestionUsing($id, $examId) {
       
        $query = sendSQLQuery("SELECT questionText, questionId FROM `QuestionTable` WHERE `questionId` = '$id'");
       
        if (!$query) {
            die('Invalid query:' .mysql_error());
        }
        
        $query2 = sendSQLQuery("SELECT questionPoints FROM `ExamQuestionPontsTable` WHERE `questionId` = '$id' AND `examID` = '$examId'");


        $question = mysql_fetch_row($query);
        $result2 = mysql_fetch_row($query2);

        //var_dump($result2);

        $question[2] = $result2[0];
        
        return $question;
    }
    
    /*
        Takes question Id and returns the function name, its testCase, and expectedReturn,
    */
    function getAnswerUsing($id) {
        $returnableArray = array();
        
        //gets a question's info
        $query = sendSQLQuery("SELECT functionName, requirement, testCaseDT FROM `QuestionTable` WHERE `questionId` = '$id'");
       
        if (!$query) {
            die('Invalid query:' .mysql_error());
        }
        
        $results = mysql_fetch_array($query, MYSQL_ASSOC);
        
        return $results;
    }
    
    
    /*
        Get exams with a certain id.
    */
    function getExamUsing($id) {
        
        $returnableArray = array();
        
        $result = sendSQLQuery("SELECT testName, questionList FROM `ExamTable` WHERE `testId` = '$id'");
        // This loop fetches the array parts from the query associatively, and adds them to an array. 
        
        $examRow = mysql_fetch_array($result, MYSQL_ASSOC);
        $returnableArray['testName'] = $examRow['testName'];
        $returnableArray['questionList'] = $examRow['questionList'];

        return $returnableArray;
    }
    
    function getStudentsExamsUsing($studentId) {
        $result = sendSQLQuery("SELECT `AssignedExamList` FROM `StudentTable` WHERE `StudentId` = $studentId");
        // This loop fetches the array parts from the query associatively, and adds them to an array. 
        
        $examIds = mysql_fetch_array($result, MYSQL_ASSOC);   
        $ids = $examIds["AssignedExamList"];
        
        
        return $ids;
    }
    
    function getExamNameFrom($id){
        $exam = getExamUsing($id);
        return $exam["testName"];
    }
    
    function getExamGradeUsing($studentId) {
    
        $returnableArray = array();
        $result = sendSQLQuery("SELECT * FROM `GradesTable` WHERE `StudentId` = $studentId");
        
        while ($row = mysql_fetch_row($result)) {
            $points = 0;
            $examName = getExamNameFrom($row[1]);
            $table_row["examName"] = $examName;
            $table_row["examNumber"] = $row[1];
            
            $result2 = sendSQLQuery("SELECT points_earned FROM ahm28.answers_table where `student_id` = '$studentId' AND exam_id = '$row[1]' ");
            
            while ($row2 = mysql_fetch_row($result2)) {
                $points += $row2[0];
            }
            
            $table_row["examGrade"] = $points; 
            $table_row["viewable"] = $row[3];
            array_push($returnableArray, $table_row);
        }
        
        return $returnableArray;    
    }
    
    function assignExamToAll($examId) {
       $result = sendSQLQuery("UPDATE `StudentTable` set `AssignedExamList`=concat(`AssignedExamList`, ',$examId')");
    }  
    
    function makeGradesViewable() {
       $result = sendSQLQuery("UPDATE `GradesTable` set `Viewable`= 'yes'");
       return $result;
    }
    
    function submitGrade($studentId, $examId, $questionId, $studentAnswer, $pointsEarned, $graderComments) {

        $result = sendSQLQuery("INSERT INTO `ahm28`.`answers_table` (`student_id`, `exam_id`, `answer`, `graders_comment`, `points_earned`, `question_id`) VALUES ('$studentId', '$examId','$studentAnswer', '$graderComments', '$pointsEarned','$questionId')");

        return $result;
    }
    
    function manuallyChangeGrade($studentId, $examId, $newGrade, $questionId ) {
     
        $result = sendSQLQuery("UPDATE ahm28.answers_table SET `points_earned`= '$newGrade' WHERE `student_id` = '$studentId' AND `exam_id` = '$examId' AND `question_Id` = '$questionId'");
       
        if (!$result) {
            die('Invalid query:' .mysql_error());
            return "false";
        } 
        return "true"; 
    }
    
    
    function submitExam ($studentId, $examId) {
        $result = sendSQLQuery("SELECT `AssignedExamList` FROM `StudentTable` WHERE `StudentId` = $studentId");
    }
    
    
    function addTestcases($testCaseList) {

        $resultHolderArray = array();

        foreach($testCaseList as $item) {          
            $cases = $item['case'];
            
            $expectedReturn = $item['expectedReturn'];

            $result = sendSQLQuery("INSERT INTO `ahm28`.`test_cases_table` (`cases`, `expected_return`) VALUES ('$cases', '$expectedReturn')");

            $result2 = sendSQLQuery("SELECT test_case_id FROM test_cases_table ORDER BY test_case_id DESC LIMIT 1");
            
            $num = mysql_fetch_array($result2, MYSQL_ASSOC);
            
            array_push($resultHolderArray, $num["test_case_id"]);
        }
        
        $string_version = implode(',', $resultHolderArray);
        return $string_version;
    }
    
        
    /*
        Sends back an array with all questions from database.
    */
    function getStudents() {
        $result = sendSQLQuery("SELECT * FROM `StudentTable`");

        if (!$result) {
            die('Invalid query:' .mysql_error());
        }
        
        $studentList = array();
        
        while ($studentRows = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $table_row['StudentId'] = $studentRows['StudentId'];
            $id = $table_row['StudentId'];
            
            $usernameQuery = sendSQLQuery("SELECT username FROM UserTable WHERE userId ='$id' ");
            
            if (!$usernameQuery) {
                die('Invalid query:' .mysql_error());
            }
            
            $name = mysql_fetch_row($usernameQuery, MYSQL_ASSOC);
            
            $table_row['name'] = $name["username"];

            array_push($studentList, $table_row);
        }
        return $studentList;
    }
    
    
    function getDetailedExamAnswers($studentId, $examId) { 
       $answersList = array();
    
        // Get the questions matching the exam ID
        $result1 = sendSQLQuery("SELECT questionList FROM ExamTable WHERE testId ='$examId' ");
        $examQuestions = mysql_fetch_array($result1, MYSQL_ASSOC);
        
        $questions = getQuestionListFrom($examQuestions["questionList"], $examId); 
        
        foreach ($questions as $question) {
           // var_dump($question);
            $table_row['questionText'] = $question['questionText'];
            $questionId = $question['questionId'];
            
            $table_row['questionPoints'] = $question['points'];

            $result2 = sendSQLQuery("SELECT answer, graders_comment, question_id, instructor_comment, points_earned FROM answers_table WHERE student_id ='$studentId' AND exam_id = '$examId' AND question_id = '$questionId' ");
            
            $results = mysql_fetch_array($result2, MYSQL_ASSOC);
            $table_row['studentAnswer'] = $results["answer"];
            
            $table_row['graderComment'] = $results["graders_comment"];
            $table_row['questionId'] = $results["question_id"];
            $table_row['instructorComment'] = $results["instructor_comment"];
            $table_row['pointsEarned'] = $results["points_earned"];
         
           array_push($answersList, $table_row);
       }
        return $answersList;
    }
    
    function addComment($studentId, $examId, $questionId, $comment){
        $result = sendSQLQuery("UPDATE `ahm28`.`answers_table` SET `instructor_comment`= '$comment' WHERE `question_id`= '$questionId' AND `exam_id` ='$examId' AND student_id = '$studentId'");
       
        if (!$result) {
            die('Invalid query:' .mysql_error());
            return "false";
        } 
        return "true"; 
    }
    
    function getTestCaseIdList($testCaseIdList) {
        $cases_list = array();
        
        $testCaseArray = convertQuestionsString($testCaseIdList);
        
        foreach ($testCaseArray as $testCaseId) {
            
            $caseList = sendSQLQuery("SELECT cases, expected_return FROM test_cases_table WHERE `test_case_id` = '$testCaseId'");
         
            $results = mysql_fetch_array($caseList, MYSQL_ASSOC);
            $table_row['testCases'] = $results["cases"];
            $table_row['expectedOutput'] = $results["expected_return"];
   
           array_push($cases_list, $table_row);
        }
        return $cases_list;
    }

    function getTestCaseIds($questionId){
        $query = sendSQLQuery("SELECT test_case_id_list FROM `QuestionTable` WHERE `questionId` = '$questionId'");
       
        $results = mysql_fetch_array($query, MYSQL_ASSOC);

        $testcases = getTestCaseIdList($results["test_case_id_list"]);

        return $testcases ;
    }

    function finalGrade($studentId, $examId) {
        
        $result = sendSQLQuery("INSERT INTO `GradesTable` (`StudentId`, `ExamId`, `Viewable`) VALUES ('$studentId', '$examId', 'no')"); 
        
        return $result;
    }
    
    function getQuestionGrade($questionID, $examID) {
        
        $query = sendSQLQuery("SELECT questionPoints FROM ahm28.ExamQuestionPontsTable WHERE `questionId`='$questionID' AND `ExamID`='$examID'");
        
        $row = mysql_fetch_assoc($query);

        return $row["questionPoints"];
    }
?>

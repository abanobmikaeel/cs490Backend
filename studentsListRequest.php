<?php
     
    /*testing*/
    // curl -d '{"test":"test"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/studentsListRequest.php
    include_once "DatabaseHandler.php";

    $studentList = getStudents();
    
    $array = array(
        "Students" => $studentList,
    );
       

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

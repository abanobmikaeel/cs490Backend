<?php
    /*
curl -d '{"studentId":"4444"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/viewGrades.php
*/
    include_once "DatabaseHandler.php";

    $json = json_decode(file_get_contents('php://input'), true);
    $exam = getExamGradeUsing($json["studentId"]);

    echo json_encode($exam, JSON_PRETTY_PRINT);
?>

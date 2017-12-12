<?php
    /*
    testing
    
    curl -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/releaseGrades.php    
    */
    include_once "DatabaseHandler.php";
    
    $result = makeGradesViewable();
    
    $array = array(
        "success"     =>  $result,
    );

    echo json_encode($array, JSON_PRETTY_PRINT);
?>

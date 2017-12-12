<?php 
    /*
    testing
    
    curl -d '{"username":"ab", "password":"password3"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/login.php
    curl -d '{"username":"bob", "password":"password1"}' -H "Content-Type: application/json" -X POST http://afsaccess1.njit.edu/~ahm28/login.php
    */
    include_once "DatabaseHandler.php";
    
    // This value is false by default since we should return false if we couldnt reach the database.
    $backendCheck = false;
    $loginJson = json_decode(file_get_contents('php://input'), true);

    $username = $loginJson["username"];
    $password = $loginJson["password"];

    $connection = connect();
    $result = loginUser($username, $password); 
    $row = mysql_fetch_array($result);

    if (count($row) > 1) {
        $backendCheck = true;
        $dbUserID = $row[1];
	
	    if ($row[0] === 's') {
	        $dbUserType = 'student';
	    }
	    else if ($row[0] === 'i') {
	        $dbUserType = 'instructor';	
	    }
    }

    $array = array(
	    "valid" => $backendCheck,
        "userType" => $dbUserType,
        "userID" => $dbUserID,
        "userName" => $username,
    );
    echo json_encode($array, JSON_PRETTY_PRINT);
?>

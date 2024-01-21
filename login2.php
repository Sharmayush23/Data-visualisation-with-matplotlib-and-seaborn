<?php

$con = mysqli_connect('localhost', 'root', 'root', 'unityaccess');
// Check if the connection happened
if (mysqli_connect_errno()) {
    echo "1: Connection failed"; // Error code #1 = connection failed    
    exit();
}

// Get username and password from POST request
$username = $_POST["name"];
$password = $_POST["password"];

// Check if the username exists
$namecheckquery = "SELECT username, salt, hash, score FROM players WHERE username='" . $username . "';";
$namecheck = mysqli_query($con, $namecheckquery) or die("2: Name check query failed"); // Error code #2 = name check query failed

if (mysqli_num_rows($namecheck) != 1) {
    echo "5: Either no user with name, or more than one"; // Error code #5 = number of names matching != 1
    exit();
}

// Get login info from the query
$existinginfo = mysqli_fetch_assoc($namecheck);
$salt = $existinginfo["salt"];
$hash = $existinginfo["hash"];

// Check if the password is correct
$loginhash = crypt($password, $salt);
if ($hash !== $loginhash) {
    echo "6: Incorrect password"; // Error code #6 = password does not hash to match table
    exit();
}

// If everything is correct, echo the user's score
echo "0\t" . $existinginfo["score"];
?>

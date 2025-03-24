<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "wpl";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['first'];
    $password = md5($_POST['password']); 

    
    $sql = "SELECT * FROM login WHERE phone = '$phone' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['login'] = $phone; 
        echo "Login successful! Redirecting...";
        header("refresh:2; url=dashboard.php"); 
        exit();
    } else {
        echo "Invalid phone number or password!";
    }
}

$conn->close();
?>

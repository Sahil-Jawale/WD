<?php
$host = 'localhost';
$dbname = 'wpl';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());f=
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $fullName = trim($_POST["fullName"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $dob = trim($_POST["dob"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $confirmPassword = trim($_POST["confirmPassword"] ?? '');
    $option = trim($_POST["option"] ?? '');
    $termsAccepted = isset($_POST["terms"]); 

   
    if (empty($fullName) || empty($email) || empty($dob) || empty($password) || empty($confirmPassword)) {
        die("Please fill in all required fields.");
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    
    $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $checkStmt = $pdo->prepare($checkEmailSql);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    $emailExists = $checkStmt->fetchColumn();

    if ($emailExists > 0) {
        die("Error: This email is already registered.");
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO users (full_name, email, dob, phone, password, option_selected) 
            VALUES (:fullName, :email, :dob, :phone, :password, :option)";
    $stmt = $pdo->prepare($sql);


    $stmt->bindParam(':fullName', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':option', $option);

    
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: Could not register.";
    }
}
?>

<?php
$host = "localhost";
$user = "root";
$port = "3306";
$dbname = "formular";
$password = "";
$theme = 'light';

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$name = $_GET["name"];
$email = $_GET["email"];
$work = $_GET["work"];

$sql = "INSERT INTO udaje (Name, email, work) VALUE ('".$name."', '".$email."', '".$work."')";

$statment = $conn->prepare($sql);

try {
    $insert = $statment->execute();
    header("location:db/spracovanieFormular.php");
    return $insert;
} catch (Exception $e) {
    return false;
}
$conn = null;

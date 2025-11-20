<?php
$host = 'localhost';       // host bazy danych
$dbname = 'sklep';         // nazwa bazy danych
$user = 'root';            // użytkownik bazy danych
$pass = '';                // hasło użytkownika

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Connection Failed: ' . $e->getMessage());
}
?>

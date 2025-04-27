<?php
$host = 'localhost';
$dbname = 'zakaznici';
$username = 'root';
$password = 'root';

try {
    // Nejprve se připojte k serveru bez specifikace databáze
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Pokusíme se vytvořit databázi, pokud neexistuje
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Poté se připojíme k nové databázi
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Chyba pripojenia: " . $e->getMessage();
}
?>
